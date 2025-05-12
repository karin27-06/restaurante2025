<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true), 
            'idCategory' => $this->faker->numberBetween(1, 5),
            'details' => $this->faker->sentence(),
            'idAlmacen' => $this->faker->numberBetween(1, 2),
            'state' => $this->faker->boolean(90), 
        ];
    }
}
