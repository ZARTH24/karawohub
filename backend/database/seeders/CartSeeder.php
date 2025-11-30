<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cart;
use App\Models\User;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        // Buat cart + 3 item per user
        $users = User::all();

        foreach ($users as $user) {
            Cart::factory()
                ->for($user)
                ->has(\App\Models\CartItem::factory()->count(3))
                ->create();
        }
    }
}
