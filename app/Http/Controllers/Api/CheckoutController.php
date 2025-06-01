<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Midtrans\Transaction as MidtransTransaction;

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
     * Checkout a product by user
     * 
     * Auth dulu bang
     * @authenticated
     */
    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'address' => 'nullable|string|max:255',
            'nego_id' => 'nullable|integer|min:0',
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
            if ($request->nego_id) {
                $nego = $user->nego()->find($request->nego_id);
                if (!$nego || $nego->status !== 'accepted') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid or inactive negotiation'
                    ], 400);
                }
                if ($nego->product_id != $request->product_id) {
                    // Check if negotiation ID matches the product ID
                    return response()->json([
                        'success' => false,
                        'message' => 'Negotiation ID does not match the request'
                    ], 400);
                }
                $product = Product::findOrFail($nego->product_id);

                $product->price = $nego->nego_price;
            } else {
                // Default product retrieval
                $product = Product::findOrFail($request->product_id);
            }


            // Check stock
            if ($product->quantity < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock'
                ], 400);
            }

            // Calculate total price
            $totalPrice = $product->price * $request->quantity + (2000 + 1000);

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
            Order::create([
                'user_id' => $user->id,
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'total_price' => $totalPrice, // Menggunakan totalPrice yang sudah dihitung
                'status' => 'pending',
                'address' => $request->address ?? $user->address, // Gunakan alamat user jika tidak ada di request
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
                    "merchant_name" => $product->user->name,
                    'category' => $product->category,
                ],

                [
                    'id' => 'APP-FEE',
                    'price' => 2000,
                    'quantity' => 1,
                    'name' => 'Biaya Aplikasi',
                ],
                [
                    'id' => 'OTH-FEE',
                    'price' => 1000,
                    'quantity' => 1,
                    'name' => 'Biaya Lain-lain',
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
     * Handle Midtrans notification
     * Ini tidak penting untuk diakses user
     * Ini hanya untuk Midtrans mengirimkan notifikasi
     */
    public function handleNotification(Request $request)
    {
        // Ambil data dari notifikasi
        $transactionStatus = $request->input('transaction_status');
        $fraudStatus = $request->input('fraud_status');
        $receipt = $request->input('order_id'); // Menggunakan order_id sebagai receipt
        $grossAmount = $request->input('gross_amount'); // Jumlah total transaksi
        $transactionId = $request->input('transaction_id'); // ID transaksi dari Midtrans

        // Temukan transaksi berdasarkan order_id
        $transaction = Transaction::where('receipt', $receipt)->first();

        if (!$transaction) {
            Log::error("Transaction not found: {$receipt}");
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Verifikasi jumlah yang diterima
        if ((float) $grossAmount != (float) $transaction->total_price) {
            Log::error("Amount mismatch for {$receipt}. Expected: {$transaction->total_price}, Received: {$grossAmount}");
            return response()->json(['message' => 'Amount mismatch'], 400);
        }
        $order = $transaction->orders()->first();
        if (!$order) {
            Log::error("Order not found for transaction: {$receipt}");
            return response()->json(['message' => 'Order not found'], 404);
        }
        DB::beginTransaction();

        try {
            // Update status transaksi di database
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $transaction->update(['status' => 'success']);
                } else {
                    $transaction->update(['status' => 'challenge']);
                }
            } elseif ($transactionStatus == 'settlement') {
                $transaction->update(['status' => 'success']);
            } elseif (
                $transactionStatus == 'cancel' ||
                $transactionStatus == 'deny' ||
                $transactionStatus == 'expire'
            ) {
                $transaction->update(['status' => 'cancelled']);
                // Restore stock jika perlu
                $this->restoreProductStock($transaction);
            } elseif ($transactionStatus == 'pending') {
                $transaction->update(['status' => 'pending']);
            }

            // Log status perubahan
            Log::info("Transaction {$receipt} status updated to {$transaction->status}");

            DB::commit();
            return response()->json(['message' => 'OK'], 200);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error processing notification: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing notification'], 500);
        }
    }



    /**
     * Get transaction status by receipt
     * This endpoint is used to check the status of a transaction by its receipt number.
     * It returns the transaction details including status, total price, payment method, and associated orders.
     * @authenticated
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
     * Get all transactions of the authenticated user
     * This endpoint retrieves all transactions made by the authenticated user.
     * It returns a list of transactions with their details including receipt, total price, status, payment method,
     * and associated orders.
     * @authenticated
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
    /**
     * Cancel a transaction
     * This endpoint allows the authenticated user to cancel a transaction.
     * @authenticated
     */
    public function cancelTransaction($id)
    {
        $transaction = auth()->user()->transaction()->find($id);
        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ], 404);
        }

        if ($transaction->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending transactions can be cancelled'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Update transaction status
            $transaction->update(['status' => 'cancelled', 'link_payment' => null]);
            // Restore product stock
            $this->restoreProductStock($transaction);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction cancelled successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel transaction: ' . $e->getMessage()
            ], 500);
        }
    }
}
