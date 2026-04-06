<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder; 
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar el caché de spatie-permission
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear roles principales
        $roleAdmin = Role::create(['name' => 'ADMINISTRADOR']);
        $roleSecretaria = Role::create(['name' => 'SECRETARIA']);

        // Crear algunos permisos básicos de ejemplo si lo deseas
        /*
        Permission::create(['name' => 'ver panel']);
        Permission::create(['name' => 'gestionar roles']);
        Permission::create(['name' => 'gestionar usuarios']);
        */

        // Asignar todos los permisos al rol Admin (si tuvieras permisos)
        // $roleAdmin->givePermissionTo(Permission::all());
    }
}
