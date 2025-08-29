<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Inventario;

class InventarioFactory extends Factory
{
    protected $model = Inventario::class;

    public function definition()
    {
        return [
            // Asume productos con IDs del 1 al 20
            'producto_id' => $this->faker->unique()->numberBetween(1, 20),
            'cantidad' => $this->faker->numberBetween(0, 100),
        ];
    }
}
