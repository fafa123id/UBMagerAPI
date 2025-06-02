<?php
namespace App\Documentation;

/**
 * @OA\Tag(
 *     name="Rating",
 *     description="API Endpoints for product rating"
 * )
 *
 * @OA\Schema(
 *     schema="Rating",
 *     required={"user_id", "product_id", "rating"},
 *     @OA\Property(property="id", type="integer", format="int64", description="Rating ID"),
 *     @OA\Property(property="user_id", type="integer", description="User ID who gave the rating"),
 *     @OA\Property(property="product_id", type="integer", description="Product ID being rated"),
 *     @OA\Property(property="rating", type="integer", minimum=1, maximum=5, description="Rating value (1-5)"),
 *     @OA\Property(property="comment", type="string", nullable=true, description="Rating comment"),
 *     @OA\Property(property="image", type="string", nullable=true, description="Rating image URL"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Update timestamp"),
 *     @OA\Property(
 *         property="user",
 *         description="User who gave the rating",
 *         ref="#/components/schemas/UserResource"
 *     ),
 *     @OA\Property(
 *         property="product",
 *         description="Product being rated",
 *         ref="#/components/schemas/ProductResource"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/rating/{id}",
 *     operationId="storeRating",
 *     tags={"Rating"},
 *     summary="Rate a product",
 *     description="Submit a rating for a product after purchase (order must be finished and not yet rated)",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Order ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"rating"},
 *                 @OA\Property(property="rating", type="integer", minimum=1, maximum=5, description="Rating value (1-5)"),
 *                 @OA\Property(property="comment", type="string", description="Rating comment"),
 *                 @OA\Property(property="image", type="string", format="binary", description="Rating image")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Rating submitted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Rating submitted successfully"),
 *             @OA\Property(property="data", ref="#/components/schemas/Rating")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Order not eligible for rating (not finished, already rated, or not owned by user)",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Order not found",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
 *     )
 * )
 */
class RatingDocumentation
{
    // This class is only for documentation purposes
}