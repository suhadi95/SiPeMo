<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LpmUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat akun LPM default
        User::query()->updateOrCreate(
            ['email' => 'lpm@uinssc.ac.id'],
            [
                'name' => 'LPM',
                'password' => 'lpm123456',
                'is_lpm' => true,
            ]
        );
    }
}
