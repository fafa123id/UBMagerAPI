<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\successReturn;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        // Get all transactions for the authenticated user
        $transactions = auth()->user()->transaction()->with('orders.product')->get();

        // Kumpulkan semua orders dari semua transaksi
        $allOrders = $transactions->flatMap->orders;

        // Kelompokkan berdasarkan status
        $pendingOrders = $allOrders->where('status', 'pending');
        $processingOrders = $allOrders->where('status', 'processing');
        $completedOrders = $allOrders->where('status', 'completed');
        $cancelledOrders = $allOrders->where('status', 'cancelled');

        // Ambil url_payment dari transaksi dengan status pending (asumsikan hanya satu)
        $pendingPaymentLink = $transactions->where('status', 'pending')->pluck('link_payment');
        return response()->json([
            'success' => true,
            'data' => [
                'pending' =>
                    [
                        $pendingOrders->get(),
                        'url_payment'->$pendingPaymentLink?: null,
                    ],
                'processing' => $processingOrders->get(),
                'completed' => $completedOrders->get(),
                'cancelled' => $cancelledOrders->get(),
            ],
        ]);
    }
    public function show($id)
    {
        // Get a specific transaction by ID for the authenticated user
        $transaction = auth()->user()->transaction()->with('orders.product')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $transaction->orders->get(),
        ]);
    }
}
