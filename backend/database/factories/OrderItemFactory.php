<?php

namespace Database\Factories;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition()
    {
        $qty = $this->faker->numberBetween(1, 5);
        $harga = $this->faker->numberBetween(10000, 50000);

        return [
            'qty' => $qty,
            'harga' => $harga,
            'subtotal' => $qty * $harga,
            // order_id dan product_id akan di-pass dari seeder supaya konsisten
        ];
    }
}
