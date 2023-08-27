<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlquilerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Alquileres sin terminar usuario admin
        \App\Models\Alquiler::factory()->count(5)->alquilerSinTerminar()->perteneceAUsuario(1)->create();

        // Alquieres terminados usuario admin
        \App\Models\Alquiler::factory()->count(5)->alquilerTerminado()->perteneceAUsuario(1)->create();

        // Alquieres sin terminar usuario normal
        \App\Models\Alquiler::factory()->count(5)->alquilerSinTerminar()->create();

        // Alquieres terminados usuario normal
        \App\Models\Alquiler::factory()->count(5)->alquilerTerminado()->create();
    }
}
