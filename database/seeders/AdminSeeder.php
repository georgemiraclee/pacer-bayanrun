<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name'     => 'Admin Bayan Run',
            'email'    => 'admin@bayanrun.com',
            'password' => Hash::make('admin123'),
        ]);

        $this->command->info('✅ Admin created: admin@bayanrun.com / admin123');
    }
}
