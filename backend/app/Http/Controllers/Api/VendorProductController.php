<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class VendorProductController extends Controller
{
    public function index(Request $r)
    {
        $vendor = $r->user()->vendor;
        return response()->json($vendor->products()->with('images')->paginate(12));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'nama'=>'required|string',
            'motif'=>'nullable|string',
            'bahan'=>'nullable|string',
            'stok'=>'required|integer|min:0',
            'harga'=>'required|integer|min:0',
            'deskripsi'=>'nullable|string',
        ]);

        $product = $r->user()->vendor->products()->create(array_merge($data,['status'=>'pending']));
        return response()->json($product,201);
    }

    public function show(Request $r, $id)
    {
        $product = $r->user()->vendor->products()->with('images')->findOrFail($id);
        return response()->json($product);
    }

    public function update(Request $r, $id)
    {
        $product = $r->user()->vendor->products()->findOrFail($id);
        $data = $r->validate([
            'nama'=>'sometimes|required|string',
            'stok'=>'sometimes|required|integer|min:0',
            'harga'=>'sometimes|required|integer|min:0',
        ]);
        $product->update($data);
        return response()->json($product);
    }

    public function destroy(Request $r, $id)
    {
        $product = $r->user()->vendor->products()->findOrFail($id);
        $product->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
