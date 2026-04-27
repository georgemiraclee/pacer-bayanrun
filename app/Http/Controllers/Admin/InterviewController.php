<?php

namespace App\Http\Controllers\Admin;

use App\Models\InterviewSession;
use App\Models\InterviewConfirmation;
use App\Services\QiscusService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class InterviewController extends Controller
{
    public function __construct(private QiscusService $qiscus) {}

    // ── Dashboard ─────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = InterviewSession::with('confirmation')->latest();

        if ($request->filled('jadwal')) {
            $query->where('jadwal', $request->jadwal);
        }

        if ($request->filled('status')) {
            match ($request->status) {
                'hadir'      => $query->whereHas('confirmation', fn($q) => $q->where('status', 'hadir')),
                'ganti_hari' => $query->whereHas('confirmation', fn($q) => $q->where('status', 'ganti_hari')),
                'belum'      => $query->whereDoesntHave('confirmation'),
                default      => null,
            };
        }

        $sessions   = $query->paginate(25)->withQueryString();
        $jadwalList = InterviewSession::select('jadwal')->distinct()->orderBy('jadwal')->pluck('jadwal');

        $stats = [
            'total'   => InterviewSession::count(),
            'hadir'   => InterviewConfirmation::where('status', 'hadir')->count(),
            'ganti'   => InterviewConfirmation::where('status', 'ganti_hari')->count(),
            'belum'   => InterviewSession::whereDoesntHave('confirmation')->count(),
            'wa_sent' => InterviewSession::where('wa_sent', true)->count(),
        ];

        $qiscusReady = !empty(config('qiscus.app_id'))
                    && !empty(config('qiscus.secret_key'))
                    && !empty(config('qiscus.channel_id'));

        $sessionData = $sessions->map(function ($s) {
            return [
                'id'   => $s->id,
                'wa'   => $s->wa_link,
                'msg'  => $s->buildWaMessage(),
                'nama' => $s->nama,
            ];
        })->values();

        return view('admin.interview.index', compact(
            'sessions', 'jadwalList', 'stats', 'qiscusReady', 'sessionData'
        ));
    }

    // ── Tambah Manual ─────────────────────────────────────────────
    public function storeManual(Request $request)
    {
    $validated = $request->validate([
        'nama'  => ['required', 'string', 'max:200'],
        'no_wa' => ['required', 'string', 'max:20'],
        'email' => ['nullable', 'email', 'max:200'],
        'jadwal'=> ['required', 'date'],
        'waktu' => ['required', 'string', 'max:10'],
        'durasi'=> ['required', 'string'],
    ]);

    // Format tanggal → "Senin, 5 Mei 2026"
    $days   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $months = ['','Januari','Februari','Maret','April','Mei','Juni',
            'Juli','Agustus','September','Oktober','November','Desember'];
    $ts     = strtotime($validated['jadwal']);
    $jadwal = $days[date('w',$ts)].', '.date('j',$ts).' '.$months[(int)date('n',$ts)].' '.date('Y',$ts);

        // Pakai jadwal custom kalau dipilih
        $jadwal = $validated['jadwal'] === '__custom__'
            ? trim($validated['jadwal_custom'] ?? '')
            : $validated['jadwal'];

        if (empty($jadwal)) {
            return back()->with('error', 'Jadwal tidak boleh kosong.');
        }

        // Cek duplikat
        $exists = InterviewSession::where('nama', $validated['nama'])
            ->where('jadwal', $jadwal)
            ->where('waktu', $validated['waktu'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Kandidat dengan nama, jadwal, dan jam yang sama sudah ada.');
        }

        InterviewSession::create([
            'token'  => InterviewSession::generateToken(),
            'nama'   => $validated['nama'],
            'no_wa'  => $validated['no_wa'],
            'email'  => $validated['email'] ?? null,
            'jadwal' => $jadwal,
            'waktu'  => $validated['waktu'],
            'durasi' => $validated['durasi'],
        ]);

        return back()->with('success', "Kandidat {$validated['nama']} berhasil ditambahkan.");
    }

    // ── Import Excel ──────────────────────────────────────────────
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240'],
        ]);

        $spreadsheet = IOFactory::load($request->file('excel_file')->getRealPath());
        $rows        = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $imported   = 0;
        $skipped    = 0;
        $currentDay = '';

        foreach ($rows as $row) {
            $no   = trim($row['A'] ?? '');
            $nama = trim($row['B'] ?? '');

            if ($no === 'No'
                || empty($nama)
                || strtolower($nama) === 'total kandidat yang lolos ke fase interview') {
                continue;
            }

            $jadwalRaw = $row['E'] ?? '';
            if (!empty($jadwalRaw) && $jadwalRaw !== $currentDay) {
                $currentDay = $this->parseJadwal($jadwalRaw);
            }

            if (empty($currentDay) || !is_numeric($no)) continue;

            $waktu = $this->parseWaktu($row['F'] ?? '');

            $exists = InterviewSession::where('nama', $nama)
                ->where('jadwal', $currentDay)
                ->where('waktu', $waktu)
                ->exists();

            if ($exists) { $skipped++; continue; }

            InterviewSession::create([
                'token'  => InterviewSession::generateToken(),
                'nama'   => $nama,
                'email'  => trim($row['C'] ?? '') ?: null,
                'no_wa'  => trim($row['D'] ?? '') ?: null,
                'jadwal' => $currentDay,
                'waktu'  => $waktu,
                'durasi' => trim($row['G'] ?? '15 Menit') ?: '15 Menit',
            ]);

            $imported++;
        }

        return back()->with('success', "Import selesai: {$imported} kandidat ditambahkan, {$skipped} sudah ada.");
    }

    // ── Hapus Single ──────────────────────────────────────────────
    public function destroy(InterviewSession $session)
    {
        $nama = $session->nama;

        DB::transaction(function () use ($session) {
            // Hapus konfirmasi dulu (jika tidak pakai cascade di migration)
            $session->confirmation()?->delete();
            $session->delete();
        });

        return back()->with('success', "Kandidat \"{$nama}\" berhasil dihapus.");
    }

    // ── Hapus Batch (selected) ────────────────────────────────────
    public function destroyBatch(Request $request)
    {
        $ids = $request->validate([
            'ids'   => ['required', 'array'],
            'ids.*' => ['integer'],
        ])['ids'];

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada ID yang dikirim.'], 422);
        }

        $count = 0;

        DB::transaction(function () use ($ids, &$count) {
            // Hapus konfirmasi terkait terlebih dahulu
            InterviewConfirmation::whereIn('interview_session_id', $ids)->delete();
            $count = InterviewSession::whereIn('id', $ids)->delete();
        });

        return response()->json([
            'success' => true,
            'message' => "{$count} kandidat berhasil dihapus.",
            'deleted' => $count,
        ]);
    }

    // ── Blast WA Single via Qiscus ────────────────────────────────
    public function blastSingle(Request $request, InterviewSession $session)
    {
        if (empty($session->no_wa)) {
            return response()->json(['success' => false, 'error' => 'Nomor WA tidak ada.'], 422);
        }

        $result = $this->qiscus->sendInterviewInvitation(
            phoneNumber: $session->no_wa,
            nama:        $session->nama,
            jadwal:      $session->jadwal,
            waktu:       $session->waktu,
            confirmLink: route('interview.confirm', $session->token),
        );

        if ($result['success']) {
            $session->update(['wa_sent' => true, 'wa_sent_at' => now()]);
        }

        return response()->json([
            'success' => $result['success'],
            'message' => $result['success']
                ? "✓ WA terkirim ke {$session->nama}"
                : "✗ Gagal: " . ($result['error'] ?? 'Unknown error'),
        ]);
    }

    // ── Blast WA Batch via Qiscus ─────────────────────────────────
    public function blastBatch(Request $request)
    {
        $ids = $request->validate([
            'ids'   => ['required', 'array'],
            'ids.*' => ['integer'],
        ])['ids'];

        $sessions = InterviewSession::whereIn('id', $ids)
            ->whereNotNull('no_wa')
            ->get();

        if ($sessions->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Tidak ada kandidat dengan nomor WA.']);
        }

        $berhasil = 0;
        $gagal    = 0;
        $errors   = [];
        $delayMs  = config('qiscus.blast_delay_ms', 1500);

        foreach ($sessions as $i => $session) {
            if ($i > 0) usleep($delayMs * 1000);

            $result = $this->qiscus->sendInterviewInvitation(
                phoneNumber: $session->no_wa,
                nama:        $session->nama,
                jadwal:      $session->jadwal,
                waktu:       $session->waktu,
                confirmLink: route('interview.confirm', $session->token),
            );

            if ($result['success']) {
                $session->update(['wa_sent' => true, 'wa_sent_at' => now()]);
                $berhasil++;
            } else {
                $gagal++;
                $errors[] = "{$session->nama}: " . ($result['error'] ?? 'error');
            }
        }

        $msg = "Blast selesai: {$berhasil} berhasil, {$gagal} gagal.";
        if ($errors) {
            $msg .= ' | ' . implode(' · ', array_slice($errors, 0, 3));
        }

        return response()->json([
            'success'  => true,
            'message'  => $msg,
            'berhasil' => $berhasil,
            'gagal'    => $gagal,
        ]);
    }

    // ── Mark WA sent batch (link manual) ─────────────────────────
    public function markWaSentBatch(Request $request)
    {
        $ids = $request->validate([
            'ids'   => ['required', 'array'],
            'ids.*' => ['integer'],
        ])['ids'];

        InterviewSession::whereIn('id', $ids)->update([
            'wa_sent'    => true,
            'wa_sent_at' => now(),
        ]);

        return response()->json(['ok' => true]);
    }

    // ── Reset semua data ──────────────────────────────────────────
    public function reset(Request $request)
    {
        if ($request->input('confirm') !== 'HAPUS') {
            return back()->with('error', 'Konfirmasi tidak cocok. Ketik "HAPUS" dengan huruf kapital.');
        }

        try {
            InterviewConfirmation::query()->delete();
            InterviewSession::query()->delete();
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }

        return redirect()->route('admin.interview.index')
            ->with('success', 'Semua data interview telah dihapus.');
    }

    // ── Export CSV ────────────────────────────────────────────────
    public function exportCsv(Request $request)
    {
        $sessions = InterviewSession::with('confirmation')
            ->when($request->filled('jadwal'), fn($q) => $q->where('jadwal', $request->jadwal))
            ->orderBy('jadwal')->orderBy('waktu')->get();

        $fn      = 'interview_confirmations_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fn}",
        ];

        $callback = function () use ($sessions) {
            $f = fopen('php://output', 'w');
            fputcsv($f, [
                'Nama', 'Email', 'No WA', 'Jadwal', 'Jam',
                'Status Konfirmasi', 'Request Hari', 'Alasan',
                'WA Blast', 'Waktu Blast', 'Waktu Konfirmasi', 'Link',
            ]);
            foreach ($sessions as $s) {
                $c = $s->confirmation;
                fputcsv($f, [
                    $s->nama,
                    $s->email ?? '-',
                    $s->no_wa ?? '-',
                    $s->jadwal,
                    $s->waktu,
                    $c ? $c->status_label : 'Belum',
                    $c?->request_hari ?? '-',
                    $c?->alasan ?? '-',
                    $s->wa_sent ? 'Ya' : 'Belum',
                    $s->wa_sent_at?->format('d/m/Y H:i') ?? '-',
                    $c?->created_at?->format('d/m/Y H:i') ?? '-',
                    route('interview.confirm', $s->token),
                ]);
            }
            fclose($f);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ── Helpers parse ─────────────────────────────────────────────
    private function parseJadwal(mixed $raw): string
    {
        $days   = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        if (is_numeric($raw)) {
            $ts = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp((float) $raw);
            return $days[date('w', $ts)] . ', ' . date('j', $ts) . ' ' . $months[(int) date('n', $ts)] . ' ' . date('Y', $ts);
        }
        if ($raw instanceof \DateTime) {
            return $days[(int) $raw->format('w')] . ', ' . $raw->format('j') . ' ' . $months[(int) $raw->format('n')] . ' ' . $raw->format('Y');
        }
        return trim((string) $raw);
    }

    private function parseWaktu(mixed $raw): string
    {
        if (is_numeric($raw)) {
            $s = (int) round((float) $raw * 86400);
            return sprintf('%02d:%02d', intdiv($s, 3600), intdiv($s % 3600, 60));
        }
        if ($raw instanceof \DateTime) return $raw->format('H:i');
        return substr(trim((string) $raw), 0, 5);
    }
}