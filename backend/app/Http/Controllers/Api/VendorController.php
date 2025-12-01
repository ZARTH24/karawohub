<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\User;
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

        // Guard clause: cek apakah user sudah vendor
        $user = $r->user();
        if ($user->role === 'vendor') {
            return response()->json([
                'message' => 'User sudah menjadi vendor'
            ], 400);
        }

        $data = $r->validate([
            'nama_toko' => 'required|string',
            'pemilik' => 'required|string',
            'legalitas' => 'nullable|string'
        ]);

        // Ubah role user
        if ($user->role !== 'vendor') {
            $user->role = 'vendor';
            $user->save();
        }

        $vendor = Vendor::create(array_merge($data, ['user_id' => $r->user()->id, 'status' => 'pending']));
        return response()->json($vendor, 201);
    }

    public function me(Request $r)
    {

        $vendor = $r->user()->vendor; // pakai property, bukan relasi method
        if (!$vendor) {
            return response()->json(['message' => 'No vendor found'], 404);
        }

        // load products secara eager
        $vendor->load('products');

        return response()->json($vendor);
    }


    public function orders(Request $r)
    {
        $vendor = $r->user()->vendor;
        if (!$vendor) {
            return response()->json(['message' => 'No vendor'], 404);
        }

        return response()->json(
            $vendor->orders()
                ->with('items', 'user', 'shipment')
                ->paginate(12)
        );
    }

    public function markReady(Request $r, $orderId)
    {
        $vendor = $r->user()->vendor;

        // Validasi kurir yang dipilih
        $data = $r->validate([
            'kurir_id' => 'required|exists:users,id'
        ]);

        // Cuma bisa ambil order milik vendor ini
        $order = $vendor->orders()->findOrFail($orderId);

        // Update status order
        $order->status = 'siap_pickup';
        $order->save();

        // Buat atau update shipment
        $shipment = $order->shipment()->updateOrCreate(
            ['order_id' => $order->id],   // kunci unik
            [
                'kurir_id' => $data['kurir_id'],
                'status'   => 'siap_pickup'
            ]
        );

        return response()->json([
            'message' => 'Order marked ready and courier assigned',
            'shipment' => $shipment
        ]);
    }

    public function couriers()
    {
        // Ambil semua user dengan role 'courier'
        $couriers = User::where('role', 'kurir')
            ->select('id', 'nama', 'email')
            ->get();

        return response()->json($couriers);
    }
}
