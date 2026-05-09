<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BalkeCandidate;
use App\Services\QiscusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BalkeController extends Controller
{
    private const TEMPLATE_NAME = 'balke_test_bayanrun';

    public function __construct(private QiscusService $qiscus) {}

    /* ─────────────────────────────────────────────
     *  INDEX — dashboard + daftar kandidat
     * ───────────────────────────────────────────── */
    public function index(Request $request)
    {
        $query = BalkeCandidate::latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) =>
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('no_wa', 'like', "%{$s}%")
            );
        }

        if ($request->filled('status')) {
            match ($request->status) {
                'terkirim' => $query->where('balke_wa_sent', true),
                'belum'    => $query->where('balke_wa_sent', false)->whereNotNull('no_wa'),
                'no_wa'    => $query->whereNull('no_wa')->orWhere('no_wa', ''),
                default    => null,
            };
        }

        $candidates = $query->paginate(25)->withQueryString();

        $stats = [
            'total'    => BalkeCandidate::count(),
            'terkirim' => BalkeCandidate::where('balke_wa_sent', true)->count(),
            'belum'    => BalkeCandidate::where('balke_wa_sent', false)
                            ->whereNotNull('no_wa')
                            ->where('no_wa', '!=', '')
                            ->count(),
            'no_wa'    => BalkeCandidate::where(fn ($q) =>
                            $q->whereNull('no_wa')->orWhere('no_wa', '')
                          )->count(),
            'gagal'    => BalkeCandidate::where('balke_wa_failed', true)->count(),
        ];

        $candidateData = $candidates->map(fn ($c) => [
            'id'    => $c->id,
            'nama'  => $c->nama,
            'no_wa' => $c->no_wa,
        ])->values();

        return view('admin.balke.index', compact('candidates', 'stats', 'candidateData'));
    }

    /* ─────────────────────────────────────────────
     *  IMPORT EXCEL
     * ───────────────────────────────────────────── */
    public function import(Request $request)
    {
    $request->validate([
        'excel_file' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240'],
    ]);

    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(
        $request->file('excel_file')->getRealPath()
    );

    $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    $imported = 0;
    $skipped  = 0;

    foreach ($rows as $i => $row) {
    if ($i === 1) continue; // skip header

    $nama    = trim($row['B'] ?? '');
    $tanggal = isset($row['E']) 
        ? \Carbon\Carbon::parse($row['E'])->translatedFormat('d F Y') 
        : null;

    $jam = $this->parseJam($row['F'] ?? '');

    // 🔥 ambil nomor WA dari kolom D
    $rawNoWa = $row['D'] ?? '';

    $no_wa = preg_replace('/[^0-9]/', '', $rawNoWa);

    if ($no_wa && str_starts_with($no_wa, '0')) {
        $no_wa = '62' . substr($no_wa, 1);
    }

    $no_wa = substr($no_wa, 0, 20);
    $no_wa = $no_wa ?: null;

    if (empty($nama) || empty($tanggal)) {
        $skipped++;
        continue;
    }

    $exists = BalkeCandidate::where('nama', $nama)
        ->where('tanggal_balke', $tanggal)
        ->exists();

    if ($exists) {
        $skipped++;
        continue;
    }

    try {
        BalkeCandidate::create([
            'nama'          => $nama,
            'email'         => $row['C'] ?? null,
            'no_wa'         => $no_wa,
            'tanggal_balke' => $tanggal,
            'jam_balke'     => $jam,
        ]);

        $imported++;
    } catch (\Exception $e) {
        $skipped++;
        \Log::error('Import error row ' . $i . ': ' . $e->getMessage(), [
            'row' => $row
        ]);
    }
}

    return back()->with(
        'success',
        "Import selesai: {$imported} berhasil, {$skipped} dilewati."
    );
}

    // ── Hapus Single ──────────────────────────────────────────────
    public function destroy(BalkeCandidate $candidate)
        {
            $nama = $candidate->nama;
            $candidate->delete();
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

            $count = BalkeCandidate::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => "{$count} kandidat berhasil dihapus.",
                'deleted' => $count,
            ]);
        }

    /* ─────────────────────────────────────────────
     *  RESET — hapus semua data
     * ───────────────────────────────────────────── */
    public function reset(Request $request)
    {
        if ($request->input('confirm') !== 'HAPUS') {
            return back()->with('error', 'Konfirmasi tidak cocok. Ketik "HAPUS" dengan huruf kapital.');
        }

        try {
            BalkeCandidate::query()->delete();
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }

        return redirect()->route('admin.balke.index')
            ->with('success', 'Semua data Balke Test telah dihapus.');
    }



    /* ─────────────────────────────────────────────
     *  HELPER — parse jam dari Excel
     * ───────────────────────────────────────────── */
    private function parseJam(mixed $raw): string
    {
        if ($raw === null || $raw === '') return '00:00';

        // Excel numeric time (fraction of day)
        if (is_numeric($raw)) {
            $s = (int) round((float) $raw * 86400);
            $h = intdiv($s, 3600) % 24;
            $m = intdiv($s % 3600, 60);
            return sprintf('%02d:%02d', $h, $m);
        }

        // DateTime object dari PhpSpreadsheet
        if ($raw instanceof \DateTime) {
            return $raw->format('H:i');
        }

        // String — ambil 5 karakter pertama (HH:MM), strip semua setelah itu
        $str = trim((string) $raw);

        // Kalau formatnya "7:00:00" atau "07:00:00 AM" dll, parse ulang
        if (preg_match('/^(\d{1,2}):(\d{2})/', $str, $m)) {
            return sprintf('%02d:%02d', (int) $m[1], (int) $m[2]);
        }

        return substr($str, 0, 5);
    }

    /* ─────────────────────────────────────────────
     *  TAMBAH MANUAL — simpan kandidat baru
     * ───────────────────────────────────────────── */
    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'nama'          => ['required', 'string', 'max:200'],
            'no_wa'         => ['nullable', 'string', 'max:20'],
            'tanggal_balke' => ['required', 'string', 'max:100'],
            'jam_balke'     => ['required', 'string', 'max:10'],
        ]);

        $exists = BalkeCandidate::where('nama', $validated['nama'])
            ->where('tanggal_balke', $validated['tanggal_balke'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Kandidat dengan nama dan tanggal yang sama sudah ada.');
        }

        BalkeCandidate::create($validated);

        return back()->with('success', "Kandidat {$validated['nama']} berhasil ditambahkan.");
    }

    /* ─────────────────────────────────────────────
     *  EXPORT CSV
     * ───────────────────────────────────────────── */
    public function exportCsv(Request $request)
    {
        $candidates = BalkeCandidate::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->where(fn ($q2) =>
                    $q2->where('nama', 'like', "%{$s}%")
                       ->orWhere('no_wa', 'like', "%{$s}%")
                );
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                match ($request->status) {
                    'terkirim' => $q->where('balke_wa_sent', true),
                    'belum'    => $q->where('balke_wa_sent', false)->whereNotNull('no_wa')->where('no_wa', '!=', ''),
                    'no_wa'    => $q->whereNull('no_wa')->orWhere('no_wa', ''),
                    default    => null,
                };
            })
            ->orderBy('tanggal_balke')
            ->orderBy('jam_balke')
            ->get();

        $fn = 'balke_test_' . now()->format('Ymd_His') . '.csv';

        return response()->stream(function () use ($candidates) {
            $f = fopen('php://output', 'w');
            fputcsv($f, ['Nama', 'No WA', 'Tanggal Test', 'Jam', 'WA Terkirim', 'Waktu Kirim', 'Gagal']);
            foreach ($candidates as $c) {
                fputcsv($f, [
                    $c->nama,
                    $c->no_wa ?? '-',
                    $c->tanggal_balke ?? '-',
                    $c->jam_balke ?? '-',
                    $c->balke_wa_sent ? 'Ya' : 'Belum',
                    $c->balke_wa_sent_at?->format('d/m/Y H:i') ?? '-',
                    $c->balke_wa_failed ? 'Ya' : 'Tidak',
                ]);
            }
            fclose($f);
        }, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fn}",
        ]);
    }

    /* ─────────────────────────────────────────────
     *  BLAST SINGLE
     * ───────────────────────────────────────────── */
    public function blastSingle(Request $request, BalkeCandidate $candidate)
    {
        if (empty($candidate->no_wa)) {
            return response()->json(['success' => false, 'message' => 'Nomor WA tidak tersedia.'], 422);
        }

        $result = $this->sendBalkeWa($candidate);

        if ($result['success']) {
            $candidate->update([
                'balke_wa_sent'    => true,
                'balke_wa_sent_at' => now(),
                'balke_wa_failed'  => false,
            ]);
        } else {
            $candidate->update(['balke_wa_failed' => true]);
        }

        return response()->json([
            'success' => $result['success'],
            'message' => $result['success']
                ? "✓ WA terkirim ke {$candidate->nama}"
                : "✗ Gagal: " . ($result['error'] ?? 'Unknown error'),
        ]);
    }

    /* ─────────────────────────────────────────────
     *  BLAST BATCH
     * ───────────────────────────────────────────── */
    public function blastBatch(Request $request)
    {
        $ids = $request->validate([
            'ids'   => ['required', 'array'],
            'ids.*' => ['integer'],
        ])['ids'];

        $candidates = BalkeCandidate::whereIn('id', $ids)
            ->whereNotNull('no_wa')
            ->where('no_wa', '!=', '')
            ->get();

        if ($candidates->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada kandidat dengan nomor WA.',
            ]);
        }

        $berhasil = 0;
        $gagal    = 0;
        $errors   = [];
        $delayMs  = config('qiscus.blast_delay_ms', 1500);

        foreach ($candidates as $i => $candidate) {
            if ($i > 0) usleep($delayMs * 1000);

            $result = $this->sendBalkeWa($candidate);

            if ($result['success']) {
                $candidate->update([
                    'balke_wa_sent'    => true,
                    'balke_wa_sent_at' => now(),
                    'balke_wa_failed'  => false,
                ]);
                $berhasil++;
            } else {
                $candidate->update(['balke_wa_failed' => true]);
                $gagal++;
                $errors[] = "{$candidate->nama}: " . ($result['error'] ?? 'error');
            }
        }

        return response()->json([
            'success'  => true,
            'message'  => "Blast selesai: {$berhasil} berhasil, {$gagal} gagal.",
            'berhasil' => $berhasil,
            'gagal'    => $gagal,
            'errors'   => $errors,
        ]);
    }

    /* ─────────────────────────────────────────────
     *  HELPER — kirim WA via Qiscus template balke_test
     * ───────────────────────────────────────────── */
    private function sendBalkeWa(BalkeCandidate $candidate): array
    {
        $phone = $this->qiscus->normalizePhone($candidate->no_wa);

        // Template balke_test hanya punya 2 parameter:
        // {{1}} = nama, {{2}} = waktu (tanggal sudah hardcode di template)
        return $this->qiscus->sendTemplate(
            phone:        $phone,
            templateName: self::TEMPLATE_NAME,
            language:     config('qiscus.template_language', 'id'),
            bodyParams: [
                ['type' => 'text', 'text' => $candidate->nama],
                ['type' => 'text', 'text' => ($candidate->jam_balke ?? '—') . ' WITA'],
            ],
        );
    }
}