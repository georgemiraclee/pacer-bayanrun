<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class QiscusService
{
    private string $baseUrl;
    private string $appId;
    private string $secretKey;
    private string $channelId;

    public function __construct()
    {
        $this->baseUrl   = rtrim(config('qiscus.base_url', 'https://omnichannel.qiscus.com'), '/');
        $this->appId     = config('qiscus.app_id', '');
        $this->secretKey = config('qiscus.secret_key', '');
        $this->channelId = config('qiscus.channel_id', '');
    }

    private function endpoint(): string
    {
        return "{$this->baseUrl}/whatsapp/v1/{$this->appId}/{$this->channelId}/messages";
    }

    public function sendInterviewInvitation(
        string $phoneNumber,
        string $nama,
        string $jadwal,
        string $waktu,
        string $confirmLink
    ): array {
        $phone = $this->normalizePhone($phoneNumber);
        $mode  = config('qiscus.send_mode', 'template');

        if ($mode === 'template') {
            return $this->sendTemplate(
                phone:        $phone,
                templateName: config('qiscus.template_name', ''),
                language:     config('qiscus.template_language', 'id'),
                bodyParams: [
                    ['type' => 'text', 'text' => $nama],
                    ['type' => 'text', 'text' => $jadwal],
                    ['type' => 'text', 'text' => $waktu . ' WITA'],
                    ['type' => 'text', 'text' => $confirmLink],
                ]
            );
        }

        return $this->sendSessionMessage($phone, $this->buildMessage($nama, $jadwal, $waktu, $confirmLink));
    }

    public function sendSessionMessage(string $phone, string $message): array
    {
        $payload = [
            'recipient_type' => 'individual',
            'to'             => $phone,
            'type'           => 'text',
            'text'           => [
                'body' => $message,
            ],
        ];

        return $this->post($payload);
    }

    public function sendTemplate(
        string $phone,
        string $templateName,
        string $language     = 'id',
        array  $bodyParams   = [],
        array  $headerParams = [],
        array  $buttonParams = [],
    ): array {
        $components = [];

        if (!empty($headerParams)) {
            $components[] = [
                'type'       => 'header',
                'parameters' => $headerParams,
            ];
        }

        if (!empty($bodyParams)) {
            $components[] = [
                'type'       => 'body',
                'parameters' => $bodyParams,
            ];
        }

        foreach ($buttonParams as $btn) {
            $components[] = $btn;
        }

        $payload = [
            'to'             => $phone,
            'type'           => 'template',
            'recipient_type' => 'individual',
            'template'       => [
                'name'       => $templateName,
                'language'   => [
                    'policy' => 'deterministic',
                    'code'   => $language,
                ],
                'components' => $components,
            ],
        ];

        return $this->post($payload);
    }

    private function buildMessage(
        string $nama,
        string $jadwal,
        string $waktu,
        string $link
    ): string {
        return "Hai Kak {$nama} 👋\n\n"
            . "Kami dari *Tim Rekrutmen Calon Pacer Bayan Run 2026* mengundang anda untuk melanjutkan seleksi ke tahap selanjutnya, yaitu *Test Interview*, yang akan dilaksanakan pada:\n\n"
            . "📅 *Hari :* {$jadwal}\n"
            . "🕐 *Jam :* {$waktu} WITA\n"
            . "📍 *Tempat :* Kantor Bayan Balikpapan\n"
            . "Jl. M.T. Haryono Komplek Balikpapan Baru Blok D4 No.8-10 (Sebrang Boyolali BB)\n\n"
            . "Silahkan klik link di bawah ini untuk mengonfirmasi kedatangan:\n"
            . "{$link}\n\n"
            . "_Tim Bayan Run 2026_";
    }

    public function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    public function isConfigured(): bool
    {
        return !empty($this->appId)
            && !empty($this->secretKey)
            && !empty($this->channelId);
    }

    private function post(array $payload): array
    {
        $url = $this->endpoint();

        try {
            Log::info('[Qiscus] Sending WA', [
                'url'      => $url,
                'to'       => $payload['to'] ?? '—',
                'type'     => $payload['type'] ?? '—',
                'template' => $payload['template']['name'] ?? null,
            ]);

            $response = Http::withHeaders([
                'Qiscus-App-Id'     => $this->appId,
                'Qiscus-Secret-Key' => $this->secretKey,
                'Content-Type'      => 'application/json',
            ])
            ->timeout(20)
            ->post($url, $payload);

            $body = $response->json();

            if ($response->successful()) {
                $msgId = $body['messages'][0]['id'] ?? null;
                Log::info('[Qiscus] Success', ['message_id' => $msgId, 'to' => $payload['to']]);
                return ['success' => true, 'data' => $body, 'message_id' => $msgId];
            }

            $errMsg = $body['error']['message']
                   ?? $body['message']
                   ?? $body['error']
                   ?? ('HTTP ' . $response->status());

            Log::error('[Qiscus] Error', [
                'status' => $response->status(),
                'body'   => $response->body(),
                'to'     => $payload['to'] ?? '—',
            ]);

            return [
                'success' => false,
                'error'   => $errMsg,
                'status'  => $response->status(),
                'body'    => $body,
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('[Qiscus] Connection error', ['message' => $e->getMessage()]);
            return ['success' => false, 'error' => 'Koneksi gagal: ' . $e->getMessage()];

        } catch (\Exception $e) {
            Log::error('[Qiscus] Exception', ['message' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}