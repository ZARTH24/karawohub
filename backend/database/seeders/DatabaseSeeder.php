<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Membership;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ------------------------------
        // Users khusus untuk testing
        // ------------------------------
        $admin = User::factory()->create([
            'nama' => 'Admin Super',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $vendorUser = User::factory()->create([
            'nama' => 'Vendor Test',
            'email' => 'vendor@test.com',
            'role' => 'vendor',
            'password' => bcrypt('password'),
        ]);

        $kurirUser = User::factory()->create([
            'nama' => 'Kurir Test',
            'email' => 'kurir@test.com',
            'role' => 'kurir',
            'password' => bcrypt('password'),
        ]);

        $regularUser = User::factory()->create([
            'nama' => 'User Biasa',
            'email' => 'user@test.com',
            'role' => 'user',
            'password' => bcrypt('password'),
        ]);

        // ------------------------------
        // User random tambahan
        // ------------------------------
        $users = User::factory(10)->create();

        // ------------------------------
        // Vendor
        // ------------------------------
        $vendors = Vendor::factory(1)->create(['user_id' => $vendorUser->id]);
        $vendors = $vendors->merge(Vendor::factory(4)->create()); // total 5 vendor

        // ------------------------------
        // Produk tiap vendor
        // ------------------------------
        foreach ($vendors as $vendor) {
            Product::factory(10)->create(['vendor_id' => $vendor->id]);
        }

        // ------------------------------
        // Cart & CartItems untuk semua user
        // ------------------------------
        $allUsers = $users->merge([$admin, $vendorUser, $kurirUser, $regularUser]);

        foreach ($allUsers as $u) {
            $cart = Cart::factory()->create(['user_id' => $u->id]);

            $products = Product::all();
            foreach ($products->random(3) as $product) {
                CartItem::factory()
                    ->forProduct($product)
                    ->create(['cart_id' => $cart->id]);
            }
        }

        // ------------------------------
        // Order & OrderItems (kecuali kurir)
        // ------------------------------
        foreach ($allUsers->whereNotIn('role', ['kurir']) as $u) {
            $orders = Order::factory(2)
                ->for($u, 'user')
                ->for($vendors->random(), 'vendor')
                ->create();

            foreach ($orders as $order) {
                $vendorProducts = Product::where('vendor_id', $order->vendor_id)->get();
                foreach ($vendorProducts->random(2) as $product) {
                    OrderItem::factory()->create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                    ]);
                }
            }
        }

        // ------------------------------
        // Membership random untuk sebagian user
        // ------------------------------
        foreach ($allUsers as $u) {
            if(rand(0,1)) {
                Membership::factory()->create(['user_id' => $u->id]);
            }
        }
    }
}
