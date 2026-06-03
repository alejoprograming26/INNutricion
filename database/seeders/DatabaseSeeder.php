<?php

namespace Database\Seeders;

use App\Models\Ajuste;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(RoleSeeder::class);
        $this->call(EstadoLaraSeeder::class);
        $this->call(MunicipioMoranSeeder::class);

        Ajuste::create([
            'nombre' => 'INNutricion',
            'descripcion' => 'Instituto Nacional de Nutrición',
            'sucursal' => 'Barquisimeto Lara',
            'direccion' => 'Calle 22 entre carrera 28 y 29, Barquisimeto, Estado Lara',
            'telefonos' => '0251-2312345',
            'email' => 'inn.gob.ve@gmail.com',
            'pagina_web' => 'www.inn.gob.ve',
        ]);

        User::create([
            'name' => 'Alejandro Alvarez',
            'email' => 'joseale260403@gmail.com',
            'password' => bcrypt('12345678'),
            'telefono' => '3121234567',
            'is_active' => true,
        ])->assignRole('ADMINISTRADOR');

        User::create([
            'name' => 'Yoelisett Jimenez',
            'email' => 'innlaramarivic@gmail.com',
            'password' => bcrypt('12345678'),
            'telefono' => '04121473081',
            'is_active' => true,
        ])->assignRole('ADMINISTRADOR');

        $this->command->info('Usuarios de prueba creados y rol Administrador asignado.');
    }
}
