<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ShipmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id' => null,
            'kurir_id' => null,
            'status' => $this->faker->randomElement([
                'siap_pickup','pickup','dalam_perjalanan',
                'hampir_sampai','sampai','gagal'
            ]),
            'bukti' => $this->faker->imageUrl(),
        ];
    }
}
