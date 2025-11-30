<?php

namespace Database\Factories;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    protected $model = CartItem::class;

    public function definition()
    {
        return [
            'qty' => $this->faker->numberBetween(1, 5),
        ];
    }

    public function forProduct(Product $product)
    {
        return $this->state(fn () => ['product_id' => $product->id]);
    }
}
