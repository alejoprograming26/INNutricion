<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parroquia;
use App\Models\Comuna;
use App\Models\Sector;

class ComunasSectoresTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parroquias = Parroquia::all();
        
        if ($parroquias->isEmpty()) {
            $this->command->warn('No hay parroquias registradas. Por favor ejecuta el seeder de EstadoLaraSeeder primero.');
            return;
        }

        foreach ($parroquias as $parroquia) {
            for ($i = 1; $i <= 5; $i++) {
                $comuna = Comuna::create([
                    'parroquia_id' => $parroquia->id,
                    'nombre' => "Comuna {$i} - " . $parroquia->nombre,
                ]);

                for ($j = 1; $j <= 4; $j++) {
                    Sector::create([
                        'comuna_id' => $comuna->id,
                        'nombre' => "Sector {$j} de " . $comuna->nombre,
                    ]);
                }
            }
        }

        $this->command->info('✅ Datos de prueba: 5 Comunas por Parroquia y 4 Sectores por Comuna generados exitosamente.');
    }
}
