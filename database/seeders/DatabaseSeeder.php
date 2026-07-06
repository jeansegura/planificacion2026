<?php

/**
 * Seeder que carga datos iniciales para flujo SIPeIP, como roles, permisos, usuarios o catalogos.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Carga datos iniciales en la base de datos.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        if (! User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        $this->call(SipeipAccessSeeder::class);
    }
}
