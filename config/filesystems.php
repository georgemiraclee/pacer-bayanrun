<?php

/**
 * config/filesystems.php
 * 
 * Tambahkan disk 'private' untuk menyimpan file sensitif (KTP, sertifikat)
 * yang tidak bisa diakses publik secara langsung via URL.
 */

return [

    'default' => env('FILESYSTEM_DISK', 'local'),

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root'   => storage_path('app/private'),
            'serve'  => true,
            'throw'  => false,
        ],

        'public' => [
            'driver'     => 'local',
            'root'       => storage_path('app/public'),
            'url'        => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw'      => false,
        ],

        // ── Disk private untuk file sensitif (KTP, sertifikat) ──
        // File di sini TIDAK bisa diakses via URL publik.
        // Hanya bisa diakses melalui controller dengan auth.
        'private' => [
            'driver'     => 'local',
            'root'       => storage_path('app/private'),
            'visibility' => 'private',
            'throw'      => false,
        ],

        // ── Contoh jika ingin pakai S3 (production) ──────────
        // Uncomment dan isi .env dengan credentials AWS:
        //
        // 's3' => [
        //     'driver'   => 's3',
        //     'key'      => env('AWS_ACCESS_KEY_ID'),
        //     'secret'   => env('AWS_SECRET_ACCESS_KEY'),
        //     'region'   => env('AWS_DEFAULT_REGION'),
        //     'bucket'   => env('AWS_BUCKET'),
        //     'url'      => env('AWS_URL'),
        //     'endpoint' => env('AWS_ENDPOINT'),
        //     'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        //     'throw'    => false,
        // ],
    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];