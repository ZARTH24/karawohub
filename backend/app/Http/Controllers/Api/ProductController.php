<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // List products with optional vendor/category filters
    public function index(Request $r)
    {
        $q = Product::with('images','vendor')
            ->where('status','aktif');

        if($r->has('vendor_id')) $q->where('vendor_id',$r->vendor_id);
        if($r->has('q')) $q->where('nama','like','%'.$r->q.'%');

        $products = $q->paginate(12);
        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::with('images','vendor')->findOrFail($id);
        if($product->status !== 'aktif'){
            return response()->json(['message'=>'Product not available'],404);
        }
        return response()->json($product);
    }
}
