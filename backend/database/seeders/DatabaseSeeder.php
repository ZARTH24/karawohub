<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipment;
use App\Models\Membership;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat admin
        User::factory()->create([
            'nama' => 'Admin Super',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        // Buat 10 user random
        $users = User::factory(10)->create();

        // Buat 5 vendor
        $vendors = Vendor::factory(5)->create()->each(function ($vendor) {
            $vendor->update([
                'user_id' => User::factory()->create(['role' => 'vendor'])->id
            ]);
        });

        // Produk untuk setiap vendor
        foreach ($vendors as $vendor) {
            Product::factory(10)->create([
                'vendor_id' => $vendor->id
            ]);
        }

        // Cart dummy
        foreach ($users as $u) {
            Cart::factory(3)->create([
                'user_id' => $u->id,
                'produk_id' => Product::inRandomOrder()->first()->id
            ]);
        }

        // Order + Item
        $orders = Order::factory(20)->create([
            'user_id' => $users->random()->id
        ]);

        foreach ($orders as $o) {
            OrderItem::factory(2)->create([
                'order_id' => $o->id,
                'produk_id' => Product::inRandomOrder()->first()->id
            ]);

            Shipment::factory()->create([
                'order_id' => $o->id,
                'kurir_id' => User::factory()->create(['role' => 'kurir'])->id
            ]);
        }

        // Membership
        foreach ($users as $u) {
            if (rand(0,1)) {
                Membership::factory()->create([
                    'user_id' => $u->id
                ]);
            }
        }
    }
}
