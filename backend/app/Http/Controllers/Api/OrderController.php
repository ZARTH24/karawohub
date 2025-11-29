<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $r)
    {
        $orders = $r->user()->orders()->with('items.product','shipment','payment')->paginate(12);
        return response()->json($orders);
    }

    public function show(Request $r, $id)
    {
        $order = $r->user()->orders()->with('items.product','shipment','payment')->findOrFail($id);
        return response()->json($order);
    }
}
