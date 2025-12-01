<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;

class PaymentController extends Controller
{
    /**
     * List semua payment (opsional, untuk admin)
     */
    public function index()
    {
        $payments = Payment::with('order')->latest()->get();
        return response()->json($payments);
    }

    /**
     * Simpan payment baru (opsional, bisa dipakai internal)
     */
    public function store(Request $request)
    {
        $payment = Payment::create([
            'order_id' => $request->order_id,
            'metode' => $request->metode,
            'status' => 'pending',
            'transaction_id' => $request->transaction_id ?? null,
        ]);

        return response()->json($payment, 201);
    }

    /**
     * Tampilkan detail payment
     */
    public function show($id)
    {
        $payment = Payment::with('order')->findOrFail($id);
        return response()->json($payment);
    }

    /**
     * Update payment (opsional, internal)
     */
    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update($request->only(['status', 'metode']));
        return response()->json($payment);
    }

    /**
     * Hapus payment (opsional, internal)
     */
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return response()->json(['message' => 'Payment deleted']);
    }

    /**
     * Webhook dari gateway (publik)
     */
    public function notify(Request $request)
    {
        // Gateway akan ngirim minimal: transaction_id & status
        $transactionId = $request->input('transaction_id');
        $status = $request->input('status'); // 'berhasil' / 'gagal'

        $payment = Payment::where('transaction_id', $transactionId)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // Update status payment
        $payment->status = $status;
        $payment->save();

        // Update order status sesuai SOP
        $order = $payment->order;
        if ($status === 'berhasil') {
            $order->status = 'bayar';
        } else {
            $order->status = 'gagal_bayar';
        }
        $order->save();

        return response()->json(['message' => 'Notification processed']);
    }
}
