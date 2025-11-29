<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VendorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => null, // nanti di seeder diisi
            'nama_toko' => $this->faker->company(),
            'pemilik' => $this->faker->name(),
            'email' => $this->faker->unique()->companyEmail(),
            'no_hp' => $this->faker->phoneNumber(),
            'alamat' => $this->faker->address(),
            'logo' => $this->faker->imageUrl(),
            'status' => $this->faker->randomElement(['aktif', 'pending', 'ditolak']),
        ];
    }
}
