<?php

namespace Database\Factories;

use App\Models\Vendor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'nama_toko' => $this->faker->company(),
            'pemilik' => $this->faker->name(),
            'legalitas' => $this->faker->randomElement(['SIUP', 'NPWP', 'LAINNYA']),
            'status' => $this->faker->randomElement(['aktif', 'pending']),
        ];
    }
}
