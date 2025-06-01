<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * GET: /api/orders
     * 
     * Display a listing of the processing orders for seller.
     * This method retrieves all orders and their associated products for the authenticated user.
     * @authenticated
     */
    public function index(Request $request)
    {
        // Get all orders for the authenticated user
        $orders = auth()->user()->ordersThroughProducts()->get();


        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }
    /**
     * GET: /api/orders/{id}/process
     * 
     * Procces order by seller after buyer pay.
     * This method retrieves the order details along with its associated products.
     * @authenticated
     */
    public function proccessOrder(Request $request, $id)
    {
        // Find the order by ID
        $order = auth()->user()->ordersThroughProducts()->findOrFail($id);

        if ($order->status !== 'processing') {
            return response()->json([
                'success' => false,
                'message' => 'Order is not in a processing state',
            ], 400);
        }
        // Update the order status to 'proccess'
        $order->update(['status' => 'proccessed']);

        return response()->json([
            'success' => true,
            'message' => 'Order is being processed',
            'data' => $order,
        ]);
    }
    /**
     * GET: /api/orders/{id}/finish
     * 
     * Finish order by buyer after buyer receive the product.
     * This method updates the order status to 'finished' by the buyer confirms receipt of the product.
     * @authenticated
     */
    public function finishOrder(Request $request, $id)
    {
        // Find the order by ID
        $order = auth()->user()->order()->findOrFail($id);

        if ($order->status !== 'proccessed') {
            return response()->json([
                'success' => false,
                'message' => 'Order is not in a processed state',
            ], 400);
        }
        // Update the order status to 'finished'
        $order->update(['status' => 'finished']);

        return response()->json([
            'success' => true,
            'message' => 'Order has been finished',
            'data' => $order,
        ]);
    }
}
