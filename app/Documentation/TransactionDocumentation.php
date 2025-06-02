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
 *     @OA\Property(property="user_id", type="integer", description="User ID who made the transaction"),
 *     @OA\Property(property="total_price", type="number", format="float", description="Total transaction amount"),
 *     @OA\Property(property="payment_method", type="string", description="Payment method used"),
 *     @OA\Property(property="status", type="string", enum={"pending","paid","cancelled","failed"}, description="Transaction status"),
 *     @OA\Property(property="receipt", type="string", description="Transaction receipt number"),
 *     @OA\Property(property="link_payment", type="string", nullable=true, description="Payment link from Midtrans"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Update timestamp")
 * )
 *
 * @OA\Post(
 *     path="/api/checkout",
 *     operationId="checkout",
 *     tags={"Transaction"},
 *     summary="Checkout a product",
 *     description="Process checkout for a product and create transaction",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"product_id", "quantity"},
 *             @OA\Property(property="product_id", type="integer", description="Product ID to checkout"),
 *             @OA\Property(property="quantity", type="integer", minimum=1, description="Quantity to purchase"),
 *             @OA\Property(property="address", type="string", description="Delivery address"),
 *             @OA\Property(property="nego_id", type="integer", nullable=true, description="Negotiation ID if using negotiated price")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Checkout successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Checkout successful"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="transaction_id", type="integer"),
 *                 @OA\Property(property="receipt", type="string"),
 *                 @OA\Property(property="payment_url", type="string"),
 *                 @OA\Property(property="total_price", type="number", format="float")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation error or insufficient stock"
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/transactions",
 *     operationId="getTransactions",
 *     tags={"Transaction"},
 *     summary="Get user transactions",
 *     description="Get all transactions for authenticated user",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Transaction"))
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/transaction/{receipt}",
 *     operationId="getTransactionByReceipt",
 *     tags={"Transaction"},
 *     summary="Get transaction by receipt",
 *     description="Get transaction details by receipt number",
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
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", ref="#/components/schemas/Transaction")
 *         )
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
 *             @OA\Property(property="message", type="string", example="Transaction cancelled successfully")
 *         )
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
 *             @OA\Property(property="order_id", type="string"),
 *             @OA\Property(property="status_code", type="string"),
 *             @OA\Property(property="transaction_status", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Notification processed successfully"
 *     )
 * )
 */
class TransactionDocumentation
{
}