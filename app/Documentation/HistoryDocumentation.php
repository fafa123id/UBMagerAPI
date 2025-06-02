<?php
namespace App\Documentation;

/**
 * @OA\Tag(
 *     name="History",
 *     description="API Endpoints for transaction history"
 * )
 *
 * @OA\Get(
 *     path="/api/history",
 *     operationId="getHistory",
 *     tags={"History"},
 *     summary="Get user transaction history",
 *     description="Get all transaction history with orders for authenticated user",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="transaction", ref="#/components/schemas/Transaction"),
 *                     @OA\Property(
 *                         property="orders",
 *                         type="array",
 *                         @OA\Items(ref="#/components/schemas/Order")
 *                     )
 *                 )
 *             )
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/history/{id}",
 *     operationId="getHistoryDetail",
 *     tags={"History"},
 *     summary="Get transaction history detail",
 *     description="Get details of a specific transaction with orders from history",
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
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 @OA\Property(property="transaction", ref="#/components/schemas/Transaction"),
 *                 @OA\Property(
 *                     property="orders",
 *                     type="array",
 *                     @OA\Items(ref="#/components/schemas/Order")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Transaction not found",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     )
 * )
 */
class HistoryDocumentation
{
    // This class is only for documentation purposes
}