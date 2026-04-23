<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transcripcion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TranscripcionesTestDataSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            'VULNERABILIDAD',
            'CPLV',
            'LACTANCIA MATERNA',
            'ENCUESTA DIETARIA',
            'MONITOREO DE PRECIO',
            'SUGIMA',
            'PERINATAL',
            'PRIMER NIVEL DE ATENCION',
            'DESNUTRICION GRAVE',
            'CONSULTA',
        ];

        // Obtener todos los sectores con sus relaciones para seleccionarlos aleatoriamente
        $sectores = DB::table('sectores')
            ->join('comunas', 'sectores.comuna_id', '=', 'comunas.id')
            ->join('parroquias', 'comunas.parroquia_id', '=', 'parroquias.id')
            ->select(
                'sectores.id as sector_id',
                'comunas.id as comuna_id',
                'parroquias.id as parroquia_id',
                'parroquias.municipio_id'
            )
            ->get()
            ->toArray();

        if (empty($sectores)) {
            $this->command->warn('No hay sectores registrados. Ejecuta los seeders de ubicaciones primero.');
            return;
        }

        // Rango de fechas: Desde el inicio del mes pasado hasta el final del mes siguiente
        $now = Carbon::now();
        $startDate = $now->copy()->subMonth()->startOfMonth();
        $endDate = $now->copy()->addMonth()->endOfMonth();
        
        $responsables = ['Alejandro Alvarez', 'Dra. Nutricionista', 'Lic. Juan Gómez', 'Lic. Ana Torres', 'Promotor Comunitario'];
        $observaciones = [
            'Jornada especial en la comunidad', 
            'Atención regular en centro', 
            'Operativo de fin de semana', 
            'Visita casa por casa', 
            'Monitoreo mensual',
            null,
            null
        ];

        $transcripciones = [];

        foreach ($tipos as $tipo) {
            for ($i = 0; $i < 100; $i++) {
                $sector = $sectores[array_rand($sectores)];
                
                $randomTimestamp = mt_rand($startDate->timestamp, $endDate->timestamp);
                $fecha = Carbon::createFromTimestamp($randomTimestamp)->format('Y-m-d');
                
                $transcripciones[] = [
                    'observacion' => $observaciones[array_rand($observaciones)],
                    'responsable' => $responsables[array_rand($responsables)],
                    'fecha' => $fecha,
                    'tipo' => $tipo,
                    'municipio_id' => $sector->municipio_id,
                    'parroquia_id' => $sector->parroquia_id,
                    'comuna_id' => $sector->comuna_id,
                    'sector_id' => $sector->sector_id,
                    'cantidad' => mt_rand(1, 50),
                    'ingreso' => mt_rand(0, 5),
                    'egreso' => mt_rand(0, 3),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insertar en bloques para que sea muy rápido
        $chunks = array_chunk($transcripciones, 500);
        foreach ($chunks as $chunk) {
            Transcripcion::insert($chunk);
        }

        $this->command->info('✅ Datos de prueba: 1000 Transcripciones generadas exitosamente (100 por cada tipo).');
    }
}
