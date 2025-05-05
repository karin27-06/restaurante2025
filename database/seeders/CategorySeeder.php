<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lista de categorías predefinidas para un restaurante
        $categories = [
            'Entrantes',          // Entrantes o aperitivos
            'Platos Principales', // Platos principales
            'Postres',            // Postres
            'Bebidas',            // Bebidas
            'Especialidades',     // Especialidades de la casa
        ];

        // Insertar las categorías en la base de datos
        foreach ($categories as $category) {
            Category::create([
                'name'   => $category,
                'state' => true,  // El estado puede ser true (activo) o false (inactivo)
            ]);
        }
    }
}
