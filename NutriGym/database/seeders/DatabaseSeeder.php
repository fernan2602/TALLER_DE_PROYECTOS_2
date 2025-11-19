<?php

namespace Database\Seeders;

use App\Models\AsignacionPreferencia;
use App\Models\User;
use Database\Factories\PromedioFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            PreferenciasSeeder::class,
            ObjetivosSeeder::class,
            UsuarioSeeder::class,
            MedidaSeeder::class,
            RolesSeeder::class,
            AsignacionPreferenciaSeeder::class,
            AsignacionObjetivoSeeder::class,
            AlimentosSeeder::class

        ]);
    }
}

        // User::factory(10)->create();

        /*User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/

