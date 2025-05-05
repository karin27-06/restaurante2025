<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create permissions

        // model users
        Permission::create(['name' => 'crear usuarios']);
        Permission::create(['name' => 'editar usuarios']);
        Permission::create(['name' => 'eliminar usuarios']);
        Permission::create(['name' => 'ver usuarios']);


        // model categorias
        Permission::create(['name' => 'crear categorias']);
        Permission::create(['name' => 'editar categorias']);
        Permission::create(['name' => 'eliminar categorias']);
        Permission::create(['name' => 'ver categorias']);
      
    }
}
