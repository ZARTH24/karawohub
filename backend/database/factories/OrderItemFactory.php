<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id' => null,
            'produk_id' => null,
            'qty' => $this->faker->numberBetween(1, 3),
            'harga' => $this->faker->numberBetween(50000, 300000),
        ];
    }
}
