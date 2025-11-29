<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => null,
            'produk_id' => null,
            'qty' => $this->faker->numberBetween(1, 5),
        ];
    }
}
