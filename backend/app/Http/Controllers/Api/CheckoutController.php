<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class CheckoutController extends Controller
{
    public function checkout(Request $r)
    {
        $data = $r->validate([
            'address'=>'required|string',
            'shipping_cost'=>'required|integer',
            'payment_method'=>'required|in:qris,va,ewallet,cod',
            'note'=>'nullable|string'
        ]);

        $user = $r->user();
        $cart = $user->cart()->with('items.product')->first();
        if(!$cart || $cart->items->isEmpty()){
            return response()->json(['message'=>'Cart kosong'],400);
        }

        // check stock for all items
        foreach($cart->items as $it){
            if($it->product->stok < $it->qty){
                return response()->json(['message'=>'Stok tidak cukup untuk '.$it->product->nama],400);
            }
        }

        return DB::transaction(function() use($user,$cart,$data){
            // For MVP we assume single vendor per order - enforce vendor uniformity
            $vendors = $cart->items->pluck('product.vendor_id')->unique();
            if($vendors->count() > 1){
                // For MVP: force frontend to create per-vendor checkout OR fail
                throw new \Exception('Multiple vendors in cart. Please checkout per vendor.');
            }
            $vendorId = $vendors->first();

            $subtotal = 0;
            foreach($cart->items as $it) $subtotal += ($it->product->harga * $it->qty);

            $total = $subtotal + $data['shipping_cost'] + ($data['payment_method'] === 'cod' ? 0 : 0);

            $order = Order::create([
                'user_id'=>$user->id,
                'vendor_id'=>$vendorId,
                'total_produk'=>$cart->items->count(),
                'ongkir'=>$data['shipping_cost'],
                'biaya_admin'=>0,
                'total'=>$total,
                'alamat'=>$data['address'],
                'catatan'=>$data['note'] ?? null,
                'status'=>'pending'
            ]);

            foreach($cart->items as $it){
                // snapshot harga
                OrderItem::create([
                    'order_id'=>$order->id,
                    'product_id'=>$it->product->id,
                    'qty'=>$it->qty,
                    'harga'=>$it->product->harga,
                    'subtotal'=> $it->product->harga * $it->qty
                ]);
                // decrement stock
                $p = Product::find($it->product->id);
                $p->stok = $p->stok - $it->qty;
                $p->save();
            }

            // create payment record (pending)
            $payment = Payment::create([
                'order_id'=>$order->id,
                'metode'=>$data['payment_method'],
                'status'=>'pending'
            ]);

            // clear cart
            $cart->items()->delete();
            $cart->delete();

            // in real life: redirect/instruct payment gateway here
            return response()->json([
                'order'=>$order->load('items'),
                'payment'=>$payment,
                'message'=>'Order created. Follow payment instructions.'
            ],201);
        });
    }
}
