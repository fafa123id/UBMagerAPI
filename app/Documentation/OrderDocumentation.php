<?php
namespace App\Documentation;
/**
 * @OA\Tag(
 *     name="Order",
 *     description="API Endpoints for order management"
 * )
 *
 * @OA\Schema(
 *     schema="Order",
 *     required={"user_id", "transaction_id", "product_id", "quantity", "total_price", "status"},
 *     @OA\Property(property="id", type="integer", format="int64", description="Order ID"),
 *     @OA\Property(property="user_id", type="integer", description="User ID who placed the order"),
 *     @OA\Property(property="transaction_id", type="integer", description="Associated transaction ID"),
 *     @OA\Property(property="product_id", type="integer", description="Product ID"),
 *     @OA\Property(property="quantity", type="integer", description="Order quantity"),
 *     @OA\Property(property="total_price", type="number", format="float", description="Total order amount"),
 *     @OA\Property(property="status", type="string", enum={"pending","processing","proccessed","finished","cancelled"}, description="Order status"),
 *     @OA\Property(property="address", type="string", description="Delivery address"),
 *     @OA\Property(property="is_rated", type="boolean", description="Whether order has been rated"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Update timestamp"),
 *     @OA\Property(
 *         property="user",
 *         description="User who placed the order",
 *         ref="#/components/schemas/UserResource"
 *     ),
 *     @OA\Property(
 *         property="transaction",
 *         description="Associated transaction",
 *         ref="#/components/schemas/Transaction"
 *     ),
 *     @OA\Property(
 *         property="product",
 *         description="Ordered product",
 *         ref="#/components/schemas/Product"
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/orders",
 *     operationId="getOrders",
 *     tags={"Order"},
 *     summary="Get seller orders",
 *     description="Get all orders for products owned by authenticated seller",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Order"))
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/orders/{id}/process",
 *     operationId="processOrder",
 *     tags={"Order"},
 *     summary="Process order",
 *     description="Mark order as processed by seller",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Order ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order processed successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Order processed successfully"),
 *             @OA\Property(property="data", ref="#/components/schemas/Order")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Order is not in processing state"
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/orders/{id}/finish",
 *     operationId="finishOrder",
 *     tags={"Order"},
 *     summary="Finish order",
 *     description="Mark order as finished by buyer",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Order ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order finished successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Order finished successfully"),
 *             @OA\Property(property="data", ref="#/components/schemas/Order")
 *         )
 *     )
 * )
 */
class OrderDocumentation
{
}