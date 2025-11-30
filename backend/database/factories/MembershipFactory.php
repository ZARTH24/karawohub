<?php

namespace Database\Factories;

use App\Models\Membership;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MembershipFactory extends Factory
{
    protected $model = Membership::class;

    public function definition()
    {
        $start = now();
        $end = now()->addMonth();

        return [
            'user_id' => User::factory(),
            'paket' => $this->faker->randomElement(['bulanan', '3 bulan', 'tahunan']),
            'aktif_mulai' => $start,
            'aktif_sampai' => $end,
            'status' => $this->faker->randomElement(['aktif', 'expired']),
        ];
    }
}
