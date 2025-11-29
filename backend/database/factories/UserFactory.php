<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'), // gausah gaya
            'role' => $this->faker->randomElement(['user', 'vendor', 'admin']),
            'foto' => $this->faker->imageUrl(),
        ];
    }
}
