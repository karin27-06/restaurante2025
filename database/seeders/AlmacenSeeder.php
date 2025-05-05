<?php

namespace Database\Seeders;

use App\Models\Almacen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlmacenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Almacen::create([
            'name' => 'Almacen Principal',
            'state' => true,
        ]);
        Almacen::create([
            'name' => 'Almacen Secundario',
            'state' => true,
        ]);
    }
}
