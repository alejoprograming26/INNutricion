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
        $roleAdmin = Role::firstOrCreate(['name' => 'ADMINISTRADOR', 'guard_name' => 'web']);
        $roleSecretaria = Role::firstOrCreate(['name' => 'SECRETARIA', 'guard_name' => 'web']);

        // Módulo Dashboard
        Permission::firstOrCreate(['name' => 'Ver Inicio', 'guard_name' => 'web'])->syncRoles($roleAdmin);

        // Módulo Ajustes
        Permission::firstOrCreate(['name' => 'Ajustes del Sistema', 'guard_name' => 'web'])->syncRoles($roleAdmin);

        // Módulo Roles
        Permission::firstOrCreate(['name' => 'Ver Listado de Roles', 'guard_name' => 'web'])->syncRoles($roleAdmin);
        Permission::firstOrCreate(['name' => 'Crear Rol', 'guard_name' => 'web'])->syncRoles($roleAdmin);
        Permission::firstOrCreate(['name' => 'Editar Rol', 'guard_name' => 'web'])->syncRoles($roleAdmin);
        Permission::firstOrCreate(['name' => 'Eliminar Rol', 'guard_name' => 'web'])->syncRoles($roleAdmin);

        // Módulo Usuarios
        Permission::firstOrCreate(['name' => 'Ver Listado de Usuarios', 'guard_name' => 'web'])->syncRoles($roleAdmin);
        Permission::firstOrCreate(['name' => 'Crear Usuario', 'guard_name' => 'web'])->syncRoles($roleAdmin);
        Permission::firstOrCreate(['name' => 'Editar Usuario', 'guard_name' => 'web'])->syncRoles($roleAdmin);
        Permission::firstOrCreate(['name' => 'Eliminar Usuario', 'guard_name' => 'web'])->syncRoles($roleAdmin);

        // Módulo Entidades (Sectores, Comunas, Metas)
        Permission::firstOrCreate(['name' => 'Ver Listado de Sectores', 'guard_name' => 'web'])->syncRoles($roleAdmin);
        Permission::firstOrCreate(['name' => 'Crear Sector', 'guard_name' => 'web'])->syncRoles($roleAdmin);
        Permission::firstOrCreate(['name' => 'Editar Sector', 'guard_name' => 'web'])->syncRoles($roleAdmin);
        Permission::firstOrCreate(['name' => 'Eliminar Sector', 'guard_name' => 'web'])->syncRoles($roleAdmin);

        Permission::firstOrCreate(['name' => 'Ver Listado de Comunas', 'guard_name' => 'web'])->syncRoles($roleAdmin);
        Permission::firstOrCreate(['name' => 'Crear Comuna', 'guard_name' => 'web'])->syncRoles($roleAdmin);
        Permission::firstOrCreate(['name' => 'Editar Comuna', 'guard_name' => 'web'])->syncRoles($roleAdmin);
        Permission::firstOrCreate(['name' => 'Eliminar Comuna', 'guard_name' => 'web'])->syncRoles($roleAdmin);

        Permission::firstOrCreate(['name' => 'Ver Listado de Metas', 'guard_name' => 'web'])->syncRoles($roleAdmin);
        Permission::firstOrCreate(['name' => 'Crear Meta', 'guard_name' => 'web'])->syncRoles($roleAdmin);
        Permission::firstOrCreate(['name' => 'Editar Meta', 'guard_name' => 'web'])->syncRoles($roleAdmin);
        Permission::firstOrCreate(['name' => 'Eliminar Meta', 'guard_name' => 'web'])->syncRoles($roleAdmin);

        // Módulo Transcripciones
        Permission::firstOrCreate(['name' => 'Ver Transcripciones', 'guard_name' => 'web'])->syncRoles($roleAdmin);

        // Módulo Actividades
        Permission::firstOrCreate(['name' => 'Ver Abordajes', 'guard_name' => 'web'])->syncRoles($roleAdmin);
        Permission::firstOrCreate(['name' => 'Ver Escuela 4S', 'guard_name' => 'web'])->syncRoles($roleAdmin);
        Permission::firstOrCreate(['name' => 'Ver Liderazgo Territorial', 'guard_name' => 'web'])->syncRoles($roleAdmin);
        Permission::firstOrCreate(['name' => 'Ver Diversidad Dietaria', 'guard_name' => 'web'])->syncRoles($roleAdmin);
        Permission::firstOrCreate(['name' => 'Ver Circulo de Lactancia', 'guard_name' => 'web'])->syncRoles($roleAdmin);
        Permission::firstOrCreate(['name' => 'Ver Plan Vulnerabilidad', 'guard_name' => 'web'])->syncRoles($roleAdmin);
        Permission::firstOrCreate(['name' => 'Ver Feria del Campo', 'guard_name' => 'web'])->syncRoles($roleAdmin);

        // Módulo Calendario
        Permission::firstOrCreate(['name' => 'Ver Calendario', 'guard_name' => 'web'])->syncRoles($roleAdmin);
        
        // Asignar permisos básicos a la SECRETARIA como ejemplo inicial
        $roleSecretaria->givePermissionTo([
            'Ver Inicio',
            'Ver Transcripciones',
            'Ver Abordajes',
            'Ver Escuela 4S',
            'Ver Liderazgo Territorial',
            'Ver Diversidad Dietaria',
            'Ver Circulo de Lactancia',
            'Ver Plan Vulnerabilidad',
            'Ver Feria del Campo',
            'Ver Calendario'
        ]);
    }
}
