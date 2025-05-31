<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Create checkout and generate Midtrans payment link
     */
    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $product = Product::findOrFail($request->product_id);
            
            // Check stock
            if ($product->quantity < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock'
                ], 400);
            }

            // Calculate total price
            $totalPrice = $product->price * $request->quantity;

            // Generate unique transaction receipt
            $receipt = 'TRX-' . time() . '-' . rand(1000, 9999);

            // Create transaction
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'total_price' => $totalPrice,
                'payment_method' => 'midtrans',
                'status' => 'pending',
                'receipt' => $receipt,
            ]);

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price,
                'status' => 'pending',
            ]);

            // Prepare Midtrans transaction data
            $transactionDetails = [
                'order_id' => $receipt,
                'gross_amount' => (int) $totalPrice,
            ];

            $itemDetails = [
                [
                    'id' => $product->id,
                    'price' => (int) $product->price,
                    'quantity' => $request->quantity,
                    'name' => $product->name,
                ]
            ];

            $customerDetails = [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
            ];

            $transactionData = [
                'transaction_details' => $transactionDetails,
                'item_details' => $itemDetails,
                'customer_details' => $customerDetails,
                'callbacks' => [
                    'finish' => url('/api/payment/finish'),
                ]
            ];

            // Get Snap redirect URL
            $snapToken = Snap::getSnapToken($transactionData);
            $paymentUrl = "https://app.sandbox.midtrans.com/snap/v2/vtweb/" . $snapToken;

            // Update transaction with payment link
            $transaction->update([
                'link_payment' => $paymentUrl
            ]);

            // Reduce product stock
            $product->decrement('quantity', $request->quantity);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Checkout successful',
                'data' => [
                    'transaction_id' => $transaction->id,
                    'receipt' => $receipt,
                    'total_price' => $totalPrice,
                    'payment_url' => $paymentUrl,
                    'snap_token' => $snapToken,
                    'order' => [
                        'product_name' => $product->name,
                        'quantity' => $request->quantity,
                        'price' => $product->price,
                    ]
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Checkout failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle Midtrans notification callback
     */
    public function handleNotification(Request $request)
    {
        try {
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;
            $receipt = $notification->order_id;

            // Find transaction
            $transaction = Transaction::where('receipt', $receipt)->first();

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found'
                ], 404);
            }

            DB::beginTransaction();

            // Update transaction and order status based on notification
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $this->updateTransactionStatus($transaction, 'challenge');
                } else if ($fraudStatus == 'accept') {
                    $this->updateTransactionStatus($transaction, 'success');
                }
            } else if ($transactionStatus == 'settlement') {
                $this->updateTransactionStatus($transaction, 'success');
            } else if ($transactionStatus == 'cancel' || 
                      $transactionStatus == 'deny' || 
                      $transactionStatus == 'expire') {
                $this->updateTransactionStatus($transaction, 'failed');
                // Restore product stock
                $this->restoreProductStock($transaction);
            } else if ($transactionStatus == 'pending') {
                $this->updateTransactionStatus($transaction, 'pending');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Notification processed successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transaction status
     */
    public function getTransactionStatus($receipt)
    {
        try {
            $transaction = Transaction::with(['orders.product', 'user'])
                ->where('receipt', $receipt)
                ->first();

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'transaction_id' => $transaction->id,
                    'receipt' => $transaction->receipt,
                    'total_price' => $transaction->total_price,
                    'status' => $transaction->status,
                    'payment_method' => $transaction->payment_method,
                    'link_payment' => $transaction->link_payment,
                    'created_at' => $transaction->created_at,
                    'orders' => $transaction->orders->map(function ($order) {
                        return [
                            'product_name' => $order->product->name,
                            'quantity' => $order->quantity,
                            'price' => $order->price,
                            'status' => $order->status,
                        ];
                    })
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get transaction status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user transactions
     */
    public function getUserTransactions()
    {
        try {
            $user = Auth::user();
            
            $transactions = Transaction::with(['orders.product'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $transactions->map(function ($transaction) {
                    return [
                        'transaction_id' => $transaction->id,
                        'receipt' => $transaction->receipt,
                        'total_price' => $transaction->total_price,
                        'status' => $transaction->status,
                        'payment_method' => $transaction->payment_method,
                        'link_payment' => $transaction->link_payment,
                        'created_at' => $transaction->created_at,
                        'orders' => $transaction->orders->map(function ($order) {
                            return [
                                'product_name' => $order->product->name,
                                'quantity' => $order->quantity,
                                'price' => $order->price,
                                'status' => $order->status,
                            ];
                        })
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get transactions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update transaction and order status
     */
    private function updateTransactionStatus($transaction, $status)
    {
        $transaction->update(['status' => $status]);
        $transaction->orders()->update(['status' => $status]);
    }

    /**
     * Restore product stock when transaction failed
     */
    private function restoreProductStock($transaction)
    {
        foreach ($transaction->orders as $order) {
            $order->product->increment('quantity', $order->quantity);
        }
    }
}
