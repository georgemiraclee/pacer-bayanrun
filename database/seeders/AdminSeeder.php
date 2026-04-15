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
            'password' => Hash::make('B4y4nRun2026!'),
        ]);

        $this->command->info('✅ Admin created: admin@bayanrun.com / B4y4nRun2026!');
    }
}
