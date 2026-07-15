<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run all mata kuliah seeders.
     */
    public function run(): void
    {
        $this->call([
            MataKuliahSeederS1::class,
            MataKuliahSeederS2::class,
            MataKuliahSeederPBA::class,
            MataKuliahSeederSPI::class,
            MataKuliahSeederHKI::class,
            MataKuliahSeederPGMI::class,
        ]);
    }
}
