<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@tzelcafe.local'],
            [
                'name' => 'TZEL Admin',
                'password' => Hash::make('Admin@8498'),
                'is_admin' => true,
            ]
        );
    }
}
