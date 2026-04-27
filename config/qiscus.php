<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Qiscus Omnichannel — WhatsApp Business API
    | Docs: https://documentation.qiscus.com/omnichannel-chat
    |--------------------------------------------------------------------------
    */

    // Base URL — dari docs resmi Qiscus
    'base_url' => env('QISCUS_BASE_URL', 'https://omnichannel.qiscus.com'),

    // App ID → Settings → App Information
    'app_id' => env('QISCUS_APP_ID', ''),

    // Secret Key → Settings → App Information
    'secret_key' => env('QISCUS_SECRET_KEY', ''),

    // Channel ID WhatsApp → Integrations → WhatsApp Integration → angka di Webhook URL
    'channel_id' => env('QISCUS_CHANNEL_ID', ''),

    // ── Template (HSM) ───────────────────────────────────────────────────────
    // Outbound → WhatsApp Broadcast Template → template APPROVED
    'template_name'     => env('QISCUS_TEMPLATE_NAME', ''),
    'template_language' => env('QISCUS_TEMPLATE_LANGUAGE', 'id'),

    // Mode kirim: "template" (HSM) atau "session" (free-form 24 jam)
    'send_mode' => env('QISCUS_SEND_MODE', 'template'),

    // Jeda antar pesan blast (ms)
    'blast_delay_ms' => (int) env('QISCUS_BLAST_DELAY_MS', 1500),

];