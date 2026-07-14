<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'uptpjj@uinssc.ac.id'],
            [
                'name' => 'Administrator',
                'password' => 'uptpjj123456',
                'is_admin' => true,
            ]
        );
        
    }
}
