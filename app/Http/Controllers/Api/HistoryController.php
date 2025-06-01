<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\successReturn;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction;

class HistoryController extends Controller
{
    /**
     * GET: /api/history
     * (Display a listing of the transactions for the authenticated user.)
     * This method retrieves all transactions and their associated orders and products.
     * @authenticated
     */
    public function index(Request $request)
    {
        // Get all transactions for the authenticated user
        $transactions = auth()->user()->transaction()->with(['orders.product', 'orders.transaction'])->get();

        // Kumpulkan semua orders dari semua transaksi
        $allOrders = $transactions->flatMap->orders;
        return response()->json([
            'success' => true,
            'data' => $allOrders,
        ]);
    }
    /**
     * GET: /api/history/{id}
     * (Get a specific transaction by ID for the authenticated user.)
     * This method retrieves the transaction details along with its associated orders and products.
     * @authenticated
     */
    public function show($id)
    {
        // Get a specific transaction by ID for the authenticated user
        $transaction = auth()->user()->transaction()->with(['orders.product', 'orders.transaction'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $transaction->orders->first(),
        ]);
    }
}