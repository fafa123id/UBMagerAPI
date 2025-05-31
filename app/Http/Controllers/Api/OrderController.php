<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Get all orders for the authenticated user
        $orders = auth()->user()->ordersThroughProducts()->get();


        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }
}
