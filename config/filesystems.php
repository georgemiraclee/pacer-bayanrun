<?php

return [

    'default' => env('FILESYSTEM_DISK', 'local'),

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root'   => storage_path('app'),
            'throw'  => false,
        ],

        'public' => [
            'driver'     => 'local',
            'root'       => storage_path('app/public'),
            'url'        => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw'      => false,
        ],

        /*
         * Disk 'private' — untuk file sensitif (KTP, sertifikat, waiver).
         * Root-nya TERPISAH dari disk 'local', ada di storage/app/private/.
         * File TIDAK bisa diakses via URL publik — hanya melalui controller admin.
         */
      'private' => [
            'driver'     => 'local',
            'root'       => storage_path('app/private'),
            'visibility' => 'private',
            'throw'      => false,  // ubah dulu
        ],
    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];