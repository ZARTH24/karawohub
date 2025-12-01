<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MembershipController extends Controller
{
    // List available packages (for MVP, we treat memberships table as subscriptions; production: separate packages table)
    public function index()
    {
        // return available paket options
        return response()->json([
            ['paket' => 'bulanan', 'days' => 30, 'price' => 100000],
            ['paket' => '3bulan', 'days' => 90, 'price' => 270000],
            ['paket' => 'tahunan', 'days' => 365, 'price' => 1000000],
        ]);
    }

    public function subscribe(Request $r)
    {
        $data = $r->validate([
            'paket' => 'required|in:bulanan,3bulan,tahunan',
            'payment_method' => 'required|in:qris,va,ewallet'
        ]);

        $user = $r->user();
        $days = $data['paket'] === 'bulanan' ? 30 : ($data['paket'] === '3bulan' ? 90 : 365);

        $now = Carbon::now(); // <<— INI DIA

        $membership = Membership::create([
            'user_id'       => $user->id,
            'paket'         => $data['paket'],
            'aktif_mulai'   => $now,
            'aktif_sampai'  => $now->copy()->addDays($days),
            'status'        => 'aktif'
        ]);

        return response()->json([
            'membership' => $membership,
            'message' => 'Membership active (MVP).'
        ]);
    }


    public function me(Request $r)
    {
        $membership = Membership::where('user_id', $r->user()->id)
            ->orderBy('id', 'desc')
            ->first();

        return response()->json([
            'membership' => $membership,
            'is_active' => $membership && $membership->status === 'aktif'
        ]);
    }
}
