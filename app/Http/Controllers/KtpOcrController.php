<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KtpOcrController extends Controller
{
    private string $ocrUrl = 'https://ktp.bayanopen.com/ocr/ktp';

    /**
     * POST /ocr/ktp
     * Menerima upload gambar KTP, teruskan ke OCR service, kembalikan data terstruktur.
     */
    public function scan(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp,heic|max:10240',
        ]);

        $file = $request->file('image');

        // ── Kirim ke OCR service ───────────────────────────────────
        try {
            $response = Http::timeout(60)
                ->withHeaders([
                    'ngrok-skip-browser-warning' => 'true',
                    'Accept'                     => 'application/json',
                ])
                ->attach(
                    'image',
                    file_get_contents($file->getRealPath()),
                    'ktp.' . $file->extension()
                )
                ->post($this->ocrUrl);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::warning('[KTP-OCR-PACER] Connection failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa konek ke OCR service. Coba lagi beberapa saat.',
            ], 503);
        } catch (\Exception $e) {
            Log::error('[KTP-OCR-PACER] Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error tidak terduga: ' . $e->getMessage(),
            ], 500);
        }

        // ── HTTP error ─────────────────────────────────────────────
        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'OCR service error (HTTP ' . $response->status() . '). Coba lagi.',
            ], 500);
        }

        // ── Parse respons ──────────────────────────────────────────
        $ocrResult = $response->json();

        if (!is_array($ocrResult) || empty($ocrResult['success'])) {
            return response()->json([
                'success' => false,
                'message' => $ocrResult['message'] ?? 'OCR gagal membaca KTP. Coba foto ulang lebih jelas dan pastikan KTP tidak buram.',
            ], 422);
        }

        $data = $ocrResult['data'] ?? $ocrResult;

        // ── Normalize fields ───────────────────────────────────────
        return response()->json([
            'success' => true,
            'data'    => [
                'nik'           => trim($data['nik']          ?? ''),
                'nama'          => trim($data['nama']         ?? ''),
                'tanggal_lahir' => $this->normalizeTanggal($data['tanggal_lahir'] ?? $data['tgl_lahir'] ?? ''),
                'tempat_lahir'  => trim($data['tempat_lahir'] ?? ''),
                'jenis_kelamin' => $this->normalizeGender($data['jenis_kelamin'] ?? ''),
                'alamat'        => trim($data['alamat']       ?? ''),
                'kota'          => strtoupper(trim($data['kota'] ?? $data['kabupaten_kota'] ?? '')),
                'agama'         => trim($data['agama']        ?? ''),
                'pekerjaan'     => trim($data['pekerjaan']    ?? ''),
            ],
        ]);
    }

    // ── Normalize gender → "L" | "P" | "" ─────────────────────────
    private function normalizeGender(string $raw): string
    {
        $r = strtoupper(trim($raw));
        if (empty($r)) return '';
        if (in_array($r, ['P', 'PR', 'WANITA', 'PEREMPUAN']) || str_contains($r, 'PEREMPUAN') || str_contains($r, 'WANITA')) return 'P';
        if (in_array($r, ['L', 'LK', 'PRIA', 'LAKI', 'LAKI-LAKI']) || str_contains($r, 'LAKI') || str_contains($r, 'PRIA')) return 'L';
        return '';
    }

    // ── Normalize tanggal → DD-MM-YYYY ────────────────────────────
    private function normalizeTanggal(string $raw): string
    {
        $raw = trim($raw);
        if (empty($raw)) return '';
        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $raw)) return $raw;
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $raw, $m))
            return sprintf('%02d-%02d-%04d', $m[1], $m[2], $m[3]);
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $raw, $m))
            return sprintf('%02d-%02d-%04d', $m[3], $m[2], $m[1]);
        return $raw;
    }
}