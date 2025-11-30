<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'vendor_id' => Vendor::factory(),
            'nama' => $this->faker->word(),
            'motif' => $this->faker->word(),
            'bahan' => $this->faker->word(),
            'stok' => $this->faker->numberBetween(0, 100),
            'harga' => $this->faker->numberBetween(10000, 100000),
            'deskripsi' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['pending', 'aktif']),
        ];
    }
}
