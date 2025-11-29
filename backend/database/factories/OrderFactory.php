<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => null,
            'alamat' => $this->faker->address(),
            'catatan' => $this->faker->sentence(),
            'metode_pembayaran' => $this->faker->randomElement(['qris', 'va', 'ewallet', 'cod']),
            'total' => $this->faker->numberBetween(75000, 500000),
            'status' => $this->faker->randomElement([
                'menunggu_pembayaran', 'diproses',
                'dikirim', 'selesai', 'gagal'
            ]),
        ];
    }
}
