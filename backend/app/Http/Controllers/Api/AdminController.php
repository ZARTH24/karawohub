<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function pendingVendors()
    {
        return response()->json(Vendor::where('status','pending')->paginate(20));
    }

    public function validateVendor(Request $r, $id)
    {
        $data = $r->validate(['status'=>'required|in:approved,rejected','alasan'=>'nullable|string']);
        $vendor = Vendor::findOrFail($id);
        $vendor->status = $data['status'] === 'approved' ? 'aktif' : 'ditolak';
        $vendor->save();

        // create vendor_validation record
        $vendor->validations()->create([
            'admin_id'=>$r->user()->id,
            'status'=>$data['status'] === 'approved' ? 'approved' : 'rejected',
            'alasan'=>$data['alasan'] ?? null
        ]);

        return response()->json(['message'=>'Vendor validated']);
    }

    public function pendingProducts()
    {
        return response()->json(Product::where('status','pending')->paginate(20));
    }

    public function validateProduct(Request $r, $id)
    {
        $data = $r->validate(['status'=>'required|in:approved,rejected','alasan'=>'nullable|string']);
        $product = Product::findOrFail($id);
        $product->status = $data['status'] === 'approved' ? 'aktif' : 'blokir';
        $product->save();
        return response()->json(['message'=>'Product updated']);
    }

    public function orders()
    {
        return response()->json(\App\Models\Order::with('items','user','vendor')->paginate(20));
    }
}
