<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Municipio;
use App\Models\Parroquia;

class EstadoLaraSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'IRIBARREN' => [
                'AGUEDO FELIPE ALVARADO', 'BUENA VISTA', 'CATEDRAL', 'CONCEPCIÓN',
                'EL CUJÍ', 'JUAN DE VILLEGAS (ANA SOTO)', 'JUÁREZ', 'SANTA ROSA',
                'TAMACA', 'UNIÓN',
            ],
            'TORRES' => [
                'ALTAGRACIA', 'ANTONIA OLAVO', 'CAMACARO', 'CASTAÑEDA', 'CHIQUINQUIRÁ',
                'EL BLANCO', 'ESPINOZA DE LOS MONTEROS', 'HERIBERTO ARROYO', 'LARA',
                'LAS MERCEDES', 'MANUEL MORILLO', 'MONTAÑA VERDE', 'MONTES DE OCA',
                'REYES VARGAS', 'TRINIDAD SAMUEL', 'TORRES',
            ],
            'JIMÉNEZ' => [
                'JUAN BAUTISTA RODRÍGUEZ', 'CUARA', 'DIEGO DE LOZADA',
                'PARAÍSO DE SAN JOSÉ', 'SAN MIGUEL', 'TINTORERO',
                'JOSÉ BERNARDO DORANTE', 'MARIANO PERAZA',
            ],
            'MORÁN' => [
                'ANZOÁTEGUI', 'BOLÍVAR', 'GUÁRICO', 'HILARIO LUNA Y LUNA',
                'HUMOCARO ALTO', 'HUMOCARO BAJO', 'LA CONCORDIA', 'MORÁN',
            ],
            'PALAVECINO' => [
                'CABUDARE', 'AGUA VIVA', 'JOSÉ GREGORIO BASTIDAS',
            ],
            'ANDRÉS ELOY BLANCO' => [
                'PÍO TAMAYO', 'YACAMBÚ', 'QUEBRADA HONDA DE GUACHE',
            ],
            'CRESPO' => [
                'FRÉITEZ', 'JOSÉ MARÍA BLANCO',
            ],
            'SIMÓN PLANAS' => [
                'SARARE', 'GUSTAVO VEGAS LEÓN', 'BURÍA',
            ],
            'URDANETA' => [
                'SIQUISIQUE', 'MOROTURO', 'SAN MIGUEL', 'XAGUAS',
            ],
        ];

        foreach ($data as $nombreMunicipio => $parroquias) {
            $municipio = Municipio::create(['nombre' => $nombreMunicipio]);

            foreach ($parroquias as $nombreParroquia) {
                Parroquia::create([
                    'municipio_id' => $municipio->id,
                    'nombre'       => $nombreParroquia,
                ]);
            }
        }

        $this->command->info('✅ Municipios y Parroquias del Estado Lara insertados correctamente.');
    }
}
