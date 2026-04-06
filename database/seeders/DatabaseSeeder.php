<?php

namespace Database\Seeders;

use App\Models\User;
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
