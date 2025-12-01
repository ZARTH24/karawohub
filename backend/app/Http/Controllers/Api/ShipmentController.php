<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function assignments(Request $r)
    {
        // courier user: list shipments assigned to this courier
        return response()->json(Shipment::where('kurir_id', $r->user()->id)->with('order')->paginate(12));
    }

    public function updateStatus(Request $r, $id)
    {
        $data = $r->validate(['status' => 'required|in:siap_pickup,pickup,dalam_perjalanan,hampir_sampai,sampai,gagal', 'bukti' => 'nullable|string']);
        $shipment = Shipment::findOrFail($id);
        // guard: only assigned courier or admin can update
        if ($shipment->kurir_id && $shipment->kurir_id !== $r->user()->id) {
            abort(403);
        }
        $shipment->update(['status' => $data['status'], 'bukti' => $data['bukti'] ?? $shipment->bukti]);
        if ($data['status'] === 'sampai') {
            $shipment->order->status = 'sampai';
            $shipment->order->save();
        }
        return response()->json($shipment);
    }

    public function calculate(Request $r)
    {
        // Frontend cuma kirim JARAK dalam kilometer
        $data = $r->validate([
            'distance_km' => 'required|numeric|min:0',
        ]);

        $km = $data['distance_km'];

        // Logika ongkir
        if ($km < 1) {
            $ongkir = 0;
        } else {
            $ongkir = ceil($km - 1) * 10000;
        }

        return response()->json([
            'jarak_km' => round($km, 2),
            'ongkir' => $ongkir,
        ]);
    }
}
