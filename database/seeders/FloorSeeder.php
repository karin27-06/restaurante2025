<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Floor;

class FloorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $floors = [
            [
                'name' => 'Piso 1',
                'description' => 'Primer piso del edificio',
                'state' => true,
            ],
            [
                'name' => 'Piso 2',
                'description' => 'Segundo piso del edificio',
                'state' => true,
            ],
            [
                'name' => 'Piso 3',
                'description' => 'Tercer piso del edificio',
                'state' => true,
            ],
        ];

        foreach ($floors as $floor) {
            Floor::create($floor);
        }
    }
}
