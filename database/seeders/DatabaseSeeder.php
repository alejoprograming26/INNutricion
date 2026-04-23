<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ajuste;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        $this->call(ComunasSectoresTestDataSeeder::class);
        $this->call(TranscripcionesTestDataSeeder::class);

       Ajuste::create([
        "nombre"=> "INNutricion",
        "descripcion" => "Instituto Nacional de Nutrición",
        "sucursal" => "Barquisimeto Lara",
        "direccion" => "Calle 22 entre carrera 28 y 29, Barquisimeto, Estado Lara",
        "telefonos" => "0251-2312345",
        "email" => "inn.gob.ve@gmail.com",
        "pagina_web" => "www.inn.gob.ve",
       ]);

       User::create([
        'name'=>'Alejandro Alvarez',
        'email' =>'joseale260403@gmail.com',
        'password' => bcrypt('12345678'),
        'telefono' => '3121234567',
        'is_active' => true,
       ])->assignRole('ADMINISTRADOR');

       User::create([
        'name'=>'Nutricionista',
        'email' =>'nutricionista@gmail.com',
        'password' => bcrypt('12345678'),
        'telefono' => '3121234567',
        'is_active' => true,
       ])->assignRole('SECRETARIA');

        
        $this->command->info('Usuarios de prueba creados y rol Administrador asignado.');
    }
}
