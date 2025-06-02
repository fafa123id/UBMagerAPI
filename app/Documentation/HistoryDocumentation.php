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
 *     description="Get all transaction history for authenticated user",
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
 *     path="/api/history/{id}",
 *     operationId="getHistoryDetail",
 *     tags={"History"},
 *     summary="Get transaction history detail",
 *     description="Get details of a specific transaction from history",
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
 *             @OA\Property(property="data", ref="#/components/schemas/Order")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Transaction not found"
 *     )
 * )
 */
class HistoryDocumentation
{
}