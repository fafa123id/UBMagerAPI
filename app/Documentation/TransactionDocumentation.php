<?php
namespace App\Documentation;

/**
 * @OA\Tag(
 *     name="Transaction",
 *     description="API Endpoints for transaction management"
 * )
 *
 * @OA\Schema(
 *     schema="Transaction",
 *     required={"user_id", "total_price", "payment_method", "status", "receipt"},
 *     @OA\Property(property="id", type="integer", format="int64", description="Transaction ID"),
 *     @OA\Property(property="user_id", type="integer", description="User ID"),
 *     @OA\Property(property="total_price", type="number", format="float", description="Total transaction price"),
 *     @OA\Property(property="payment_method", type="string", description="Payment method"),
 *     @OA\Property(property="status", type="string", enum={"pending","success","failed","cancelled"}, description="Transaction status"),
 *     @OA\Property(property="receipt", type="string", description="Transaction receipt number"),
 *     @OA\Property(property="link_payment", type="string", nullable=true, description="Payment link from Midtrans"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(
 *         property="user",
 *         description="User who made the transaction",
 *         ref="#/components/schemas/UserResource"
 *     ),
 *     @OA\Property(
 *         property="orders",
 *         description="Orders associated with this transaction",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Order")
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/checkout",
 *     operationId="checkout",
 *     tags={"Transaction"},
 *     summary="Checkout a product",
 *     description="Process product checkout and create transaction",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"product_id", "quantity"},
 *             @OA\Property(property="product_id", type="integer", description="Product ID"),
 *             @OA\Property(property="quantity", type="integer", minimum=1, description="Quantity to purchase"),
 *             @OA\Property(property="address", type="string", description="Delivery address"),
 *             @OA\Property(property="nego_id", type="integer", description="Negotiation ID if using negotiated price")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Checkout successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Transaction created successfully"),
 *             @OA\Property(
 *                 property="data",
 *                 @OA\Property(property="transaction", ref="#/components/schemas/Transaction"),
 *                 @OA\Property(property="payment_url", type="string", description="Midtrans payment URL")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Insufficient stock or invalid negotiation",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/transactions",
 *     operationId="getUserTransactions",
 *     tags={"Transaction"},
 *     summary="Get user transactions",
 *     description="Retrieve all transactions for the authenticated user",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Transactions retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Transaction")
 *             )
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/transaction/{receipt}",
 *     operationId="getTransactionStatus",
 *     tags={"Transaction"},
 *     summary="Get transaction status",
 *     description="Get transaction status by receipt number",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="receipt",
 *         in="path",
 *         description="Transaction receipt number",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Transaction status retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", ref="#/components/schemas/Transaction")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Transaction not found",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/transaction/cancel/{id}",
 *     operationId="cancelTransaction",
 *     tags={"Transaction"},
 *     summary="Cancel transaction",
 *     description="Cancel a pending transaction",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Transaction ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Transaction cancelled successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="data", ref="#/components/schemas/Transaction")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Cannot cancel transaction",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/midtrans/notification",
 *     operationId="midtransNotification",
 *     tags={"Transaction"},
 *     summary="Midtrans payment notification",
 *     description="Handle payment notification from Midtrans",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="order_id", type="string", description="Order ID"),
 *             @OA\Property(property="status_code", type="string", description="Status code"),
 *             @OA\Property(property="transaction_status", type="string", description="Transaction status")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Notification processed successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string")
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/payment/finish",
 *     operationId="paymentFinish",
 *     tags={"Transaction"},
 *     summary="Payment finish callback",
 *     description="Handle payment finish callback from Midtrans",
 *     @OA\Parameter(
 *         name="order_id",
 *         in="query",
 *         description="Order ID",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="status_code",
 *         in="query",
 *         description="Status code",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="transaction_status",
 *         in="query",
 *         description="Transaction status",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Payment finish processed successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string")
 *         )
 *     )
 * )
 */
class TransactionDocumentation
{
    // This class is only for documentation purposes
}