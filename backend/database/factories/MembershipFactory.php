<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MembershipFactory extends Factory
{
    public function definition(): array
    {
        $mulai = now();
        $paket = $this->faker->randomElement(['bulanan', '3bulan', 'tahunan']);

        $durasi = match ($paket) {
            'bulanan' => 30,
            '3bulan' => 90,
            'tahunan' => 365,
        };

        return [
            'user_id' => null,
            'paket' => $paket,
            'aktif_mulai' => $mulai,
            'aktif_sampai' => $mulai->copy()->addDays($durasi),
            'status' => 'aktif',
        ];
    }
}
