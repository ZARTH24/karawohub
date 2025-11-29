<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'vendor_id' => null,
            'nama' => $this->faker->word(),
            'deskripsi' => $this->faker->sentence(10),
            'bahan' => $this->faker->randomElement(['katun', 'sutra', 'wol']),
            'motif' => $this->faker->randomElement(['karawo', 'modern', 'batik']),
            'harga' => $this->faker->numberBetween(50000, 300000),
            'stok' => $this->faker->numberBetween(0, 50),
            'rating' => $this->faker->randomFloat(2, 1, 5),
            'foto' => $this->faker->imageUrl(),
            'status' => 'aktif',
        ];
    }
}
