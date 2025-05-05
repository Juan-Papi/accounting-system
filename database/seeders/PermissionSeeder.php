<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    
        $adminPermissions = [
            // Roles
            'Crear role',
            'Listar role',
            'Editar role',
            'Eliminar role',
    
            'Listar usuarios',
            'Editar usuarios',
            'Crear usuarios',
            'Eliminar usuarios',
            'Administrar usuarios',
            
            'Ver dashboard',

            'Vistar bitacora',
        ];

        $managerPermissions = [
            'Listar usuarios',
            'Editar usuarios',
            'Crear usuarios',
            'Eliminar usuarios',
            'Administrar usuarios',
            
            'Crear compras',
            'Listar compras',
            'Actualizar compras',
            'Eliminar compras',

            'Crear ventas',
            'Listar ventas',
            'Actualizar ventas',
            'Eliminar ventas',

            'Ver dashboard'
        ];

        $executivePermissions = [
            'Crear ventas',
            'Listar ventas',
            'Actualizar ventas',
            'Eliminar ventas',

            'Ver dashboard'
        ];

        foreach ($adminPermissions as $name) {
            Permission::firstOrCreate(['name' => $name]);
        }

        foreach ($managerPermissions as $name) {
            Permission::firstOrCreate(['name' => $name]);
        }

        foreach ($executivePermissions as $name) {
            Permission::firstOrCreate(['name' => $name]);
        }

        $admin = Role::where('name', 'Admin')->first();
        $manager = Role::where('name', 'Gerente')->first();
        $executive = Role::where('name', 'Ejecutivo de ventas')->first();


        $admin->syncPermissions($adminPermissions);
        $manager->syncPermissions($managerPermissions);
        $executive->syncPermissions($executivePermissions);

    }
}
