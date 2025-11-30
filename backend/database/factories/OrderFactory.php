<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        $totalProduk = $this->faker->numberBetween(1, 5);
        $ongkir = $this->faker->numberBetween(5000, 20000);
        $biayaAdmin = $this->faker->numberBetween(1000, 5000);
        $subtotal = $totalProduk * $this->faker->numberBetween(10000, 50000);

        return [
            'user_id' => User::factory(),
            'vendor_id' => Vendor::factory(),
            'total_produk' => $totalProduk,
            'ongkir' => $ongkir,
            'biaya_admin' => $biayaAdmin,
            'total' => $subtotal + $ongkir + $biayaAdmin,
            'alamat' => $this->faker->address(),
            'catatan' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['pending', 'selesai', 'dibatalkan']),
        ];
    }
}
