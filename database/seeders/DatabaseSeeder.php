<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            LpmUserSeeder::class,
            JurusanSeeder::class,
            MataKuliahSeederS1::class,
            MataKuliahSeederS2::class,
        ]);
    }
}
