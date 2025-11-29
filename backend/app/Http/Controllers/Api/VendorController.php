<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        return response()->json(Vendor::with('user')->paginate(12));
    }

    public function show($id)
    {
        return response()->json(Vendor::with('products')->findOrFail($id));
    }

    public function register(Request $r)
    {
        $data = $r->validate([
            'nama_toko'=>'required|string',
            'pemilik'=>'required|string',
            'legalitas'=>'nullable|string'
        ]);

        $vendor = Vendor::create(array_merge($data,['user_id'=>$r->user()->id, 'status'=>'pending']));
        return response()->json($vendor,201);
    }

    public function me(Request $r)
    {
        return response()->json($r->user()->vendor()->with('products')->first());
    }

    public function orders(Request $r)
    {
        $vendor = $r->user()->vendor;
        if(!$vendor) return response()->json(['message'=>'No vendor'],404);
        return response()->json($vendor->orders()->with('items','user')->paginate(12));
    }

    public function markReady(Request $r, $orderId)
    {
        $vendor = $r->user()->vendor;
        $order = $vendor->orders()->findOrFail($orderId);
        $order->status = 'siap_pickup';
        $order->save();
        // create/update shipment entry
        $order->shipment()->updateOrCreate(['order_id'=>$order->id],['status'=>'siap_pickup']);
        return response()->json(['message'=>'Order marked ready']);
    }
}
