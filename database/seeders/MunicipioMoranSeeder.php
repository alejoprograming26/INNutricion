<?php

namespace Database\Seeders;

use App\Models\Municipio;
use App\Models\Parroquia;
use App\Models\Comuna;
use App\Models\Sector;
use Illuminate\Database\Seeder;

class MunicipioMoranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $municipio = Municipio::where('nombre', 'MORÁN')->first();

        if (!$municipio) {
            $this->command->error('No se encontró el Municipio MORÁN. Asegúrate de correr EstadoLaraSeeder primero.');
            return;
        }

        $data = [
            'HUMOCARO BAJO' => [
                'EN CONSTRUCCION' => [
                    'EL CERRITO', 'LAS RURALES'
                ],
                'LUCHADORES DEL PENON DE GUAYONES' => [
                    'EL BEIBOL', 'EL PANTANO', 'EL TELEFERICO', 'VALLE BLANCO'
                ],
            ],
            'HUMOCARO ALTO' => [
                'PARAMO DE SENDE' => [
                    'U.E.GUAYANTA (LA COSTA)'
                ],
                'REVOLUCION EN MARCHA' => [
                    'ARENALES', 'MIRA FLORES', 'PASO ANCHO'
                ],
                'SOCIALISTA COMANDANTE SUPREMO' => [
                    'BARRIO AJURO'
                ],
            ],
            'GUÁRICO' => [
                'SOCIALISTA GUARICA TIERRA GUAYONA' => [
                    'AGUA BLANCA', 'LA PRIMAVERA', 'LOS COLORADITOS'
                ],
            ],
            'BOLÍVAR' => [
                'CIMARRONA' => [
                    'CIMARRONA'
                ],
                'DR FRANCISCO TAMAYO' => [
                    'BRISAS DE PUEBLO NUEVO', 'CARABINERA', 'DOS CAMINOS', 'ESTRELLA',
                    'ESTRELLA 1', 'ESTRELLA 2', 'HATICO DE DIOS', 'HATICO SANTA LUCIA',
                    'PALO VERDE', 'SAN JOSE', 'VILLA PALMARES', 'VIRGEN DEL CARMEN', 'YOGORE'
                ],
                'REVOLUCION SOCIALISTA' => [
                    '19 DE ABRIL VALVANERA TABATOCA'
                ],
                'SOCIALISTA JOSE TRINIDAD MORAN' => [
                    'CASERIO EL BOSQUE SECTOR I', 'FRANCISCO SUAREZ', 'TABATOCA', 'VALVANERA'
                ],
                'UNION DE LOS BOROS' => [
                    'BORO', 'BORO SANTA TERESA'
                ],
                'EN CONSTRUCCION MARIA FONSECA' => [
                    'EL JEVITO'
                ],
                'EN CONSTRUCCION PATRICIA LEAL' => [
                    'BARRIO COROMOTO', 'CAPITOLIO', 'CONCORDIA', 'DANIEL CARIAS',
                    'EL CEMENTERIO', 'EL POZON', 'LA COQUETA', 'LAS ESTACAS',
                    'LOS ROBLES', 'LOS HORNOS', 'LOS LOROS', 'URB. NUBIA'
                ],
                'EN CONSTRUCCION ROLANDO LUGO' => [
                    'COLINAS DE MARCO PERDOMOS', 'CORPAHUAICO', 'EL ENCANTO'
                ],
                'GIGANTE DE AMERICA DEL SIGLO XXI' => [
                    'LAGUNITA'
                ],
                'GREGORIO ANTONIO CASTILLO' => [
                    'LA OTRA BANDA', 'OSPINAL', 'QUEBRADA SECA'
                ],
                'PLACIDO LOPEZ' => [
                    'CAMPO LINDO', 'SAN ANTONIO'
                ],
                'COMO OLVIDARTE COMANDANTE' => [
                    'EL MAMON', 'EL MOLINO', 'GUAJIRITA', 'LOS EJIDOS',
                    'MESIT BAJA', 'SANTA RITA', 'ZARANDA'
                ],
                'CORREDOR SECTOR COMUNAL' => [
                    'ROBERTO MONTESINO', 'SANTA EDUVIGES'
                ],
                'DR. FRANCISCO TAMAYO' => [
                    'PUEBLO NUEVO I'
                ],
                'MARIA GERONIMA AGUILAR' => [
                    'BUENA VISTA', 'LAS PALMAS', 'SANBENITO I'
                ],
            ],
            'MORÁN' => [
                'CAPITAN CARMELO MENDOZA' => [
                    'HATO ARRIBA'
                ],
                'COMUNA BARBACOA' => [
                    'BARBACOA'
                ],
            ],
        ];

        foreach ($data as $nombreParroquia => $comunas) {
            $parroquia = Parroquia::where('municipio_id', $municipio->id)
                ->where('nombre', $nombreParroquia)
                ->first();

            if (!$parroquia) {
                $parroquia = Parroquia::create([
                    'municipio_id' => $municipio->id,
                    'nombre' => $nombreParroquia
                ]);
            }

            foreach ($comunas as $nombreComuna => $sectores) {
                $comuna = Comuna::firstOrCreate([
                    'parroquia_id' => $parroquia->id,
                    'nombre' => $nombreComuna
                ]);

                foreach ($sectores as $nombreSector) {
                    Sector::firstOrCreate([
                        'comuna_id' => $comuna->id,
                        'nombre' => $nombreSector
                    ]);
                }
            }
        }

        $this->command->info('✅ Comunas y Sectores del Municipio Morán insertados con éxito.');
    }
}
