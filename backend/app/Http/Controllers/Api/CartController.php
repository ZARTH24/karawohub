<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show(Request $r)
    {
        $cart = $r->user()->cart()->with('items.product.images')->first();
        if(!$cart) {
            return response()->json(['items'=>[]]);
        }
        return response()->json($cart);
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'product_id'=>'required|exists:products,id',
            'qty'=>'required|integer|min:1'
        ]);

        $product = Product::findOrFail($data['product_id']);
        if($product->stok < $data['qty']){
            return response()->json(['message'=>'Stok Tidak Tersedia'],400);
        }

        $cart = $r->user()->cart()->firstOrCreate(['user_id'=>$r->user()->id]);

        $item = $cart->items()->where('product_id',$product->id)->first();
        if($item){
            $item->qty += $data['qty'];
            $item->save();
        } else {
            $cart->items()->create([
                'product_id'=>$product->id,
                'qty'=>$data['qty']
            ]);
        }

        return response()->json(['message'=>'Added to cart']);
    }

    public function update(Request $r, $id)
    {
        $data = $r->validate(['qty'=>'required|integer|min:1']);
        $item = CartItem::findOrFail($id);
        // guard: ensure it belongs to user's cart
        if($item->cart->user_id !== $r->user()->id) abort(403);
        $product = $item->product;
        if($product->stok < $data['qty']) {
            return response()->json(['message'=>'Stok Tidak Cukup'],400);
        }
        $item->qty = $data['qty'];
        $item->save();
        return response()->json($item);
    }

    public function destroy(Request $r, $id)
    {
        $item = CartItem::findOrFail($id);
        if($item->cart->user_id !== $r->user()->id) abort(403);
        $item->delete();
        return response()->json(['message'=>'Removed']);
    }
}
