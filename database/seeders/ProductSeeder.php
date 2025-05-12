<?php
namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'Hamburguesa', 'Pizza', 'Ensalada César', 'Tarta de queso', 'Coca-Cola',
            'Paella', 'Tacos', 'Helado de vainilla', 'Agua mineral', 'Costillas BBQ'
        ];

        // Definir almacenes disponibles
        $idAlmacenes = [1, 2];
        
        // Categorías de ejemplo (rango del 1 al 5)
        $idCategorias = range(1, 5);

        // Estados posibles: "activo" o "inactivo"
        $states = ['activo', 'inactivo'];

        // Contador para limitar la creación a 10 productos
        $createdCount = 0;

        // Crear hasta 10 productos
        foreach ($idAlmacenes as $idAlmacen) {
            foreach ($names as $name) {
                if ($createdCount >= 10) {
                    return; // Si ya se crearon 10 productos, termina el ciclo
                }

                // Asignar una categoría aleatoria de $idCategorias
                $randomCategory = $idCategorias[array_rand($idCategorias)];

                // Asignar un estado aleatorio de $states
                $randomState = $states[array_rand($states)];

                Product::create([
                    'name' => "$name",
                    'idCategory' => $randomCategory,
                    'details' => "Descripción de $name para categoría $randomCategory y almacén $idAlmacen.",
                    'idAlmacen' => $idAlmacen,
                    'state' => $randomState === 'activo', // Convertir el estado en un valor booleano (activo o inactivo)
                ]);

                $createdCount++; // Aumentar el contador de productos creados
            }
        }
    }
}
