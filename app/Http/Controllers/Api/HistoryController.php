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
     * 
     * Display a listing of the transactions for the authenticated user.
     * This method retrieves all transactions and their associated orders and products.
     * @authenticated
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $page = $request->query('page');
        $perpage = $request->query('perpage');
        // Get all transactions for the authenticated user
        $transactions = auth()->user()->transaction()->with(['orders.product', 'orders.transaction','products.user']);
        if ($status) {
            $transactions->whereHas('orders', function ($query) use ($status) {
                $query->where('status', $status);
            });
        }
        $allCount = $transactions->count();
        if ($page && $perpage) {
            $transactions = $transactions->skip(($page - 1) * $perpage)->take($perpage);
        }
        $transactions = $transactions->get();

        // Kumpulkan semua orders dari semua transaksi
        $allOrders = $transactions->flatMap->orders;
        return response()->json([
            'success' => true,
            'data' => $allOrders,
            'meta' => [
                'page' => $page ?? 1,
                'perpage' => $perpage ?? 5,
                'total' => $allCount,
                'last_page' => ceil($allCount / ($perpage ?? 5)),
            ]
        ]);
    }
    /**
     * GET: /api/history/{id}
     * 
     * Get a specific transaction by ID for the authenticated user.
     * 
     * This method retrieves the transaction details along with its associated orders and products.
     * @authenticated
     */
    public function show($id)
    {
        // Get a specific transaction by ID for the authenticated user
        $transaction = auth()->user()->transaction()->with(['orders.product', 'orders.transaction','products.user'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $transaction->orders->first(),
        ]);
    }
}
