<?php
namespace App\Documentation;
/**
 * @OA\Schema(
 *     schema="UserResource",
 *     title="User Resource",
 *     description="User resource representation",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="role_id", type="integer", description="User role ID"),
 *     @OA\Property(property="status", type="string", description="User status"),
 *     @OA\Property(property="phone", type="string", example="1234567890"),
 *     @OA\Property(property="address", type="string", nullable=true, example="123 Main St"),
 *     @OA\Property(property="image", type="string", nullable=true, example="https://example.com/image.jpg"),
 *     @OA\Property(property="rating", type="number", format="float", nullable=true, description="Average rating for sellers"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z"),
 *     @OA\Property(
 *         property="products",
 *         type="array",
 *         description="Products owned by this user",
 *         @OA\Items(ref="#/components/schemas/Product")
 *     ),
 *     @OA\Property(
 *         property="transactions",
 *         type="array",
 *         description="Transactions made by this user",
 *         @OA\Items(ref="#/components/schemas/Transaction")
 *     ),
 *     @OA\Property(
 *         property="orders",
 *         type="array",
 *         description="Orders placed by this user",
 *         @OA\Items(ref="#/components/schemas/Order")
 *     ),
 *     @OA\Property(
 *         property="negotiations",
 *         type="array",
 *         description="Negotiations initiated by this user",
 *         @OA\Items(ref="#/components/schemas/Nego")
 *     ),
 *     @OA\Property(
 *         property="receivedNegos",
 *         type="array",
 *         description="Negotiations received for user's products",
 *         @OA\Items(ref="#/components/schemas/Nego")
 *     ),
 *     @OA\Property(
 *         property="ratings",
 *         type="array",
 *         description="Ratings given by this user",
 *         @OA\Items(ref="#/components/schemas/Rating")
 *     ),
 *     @OA\Property(
 *         property="resetToken",
 *         description="Password reset token",
 *         ref="#/components/schemas/ResetToken"
 *     )
 * )
 *
 * @OA\Tag(
 *     name="Users",
 *     description="API endpoints for User management operations"
 * )
 * 
 * @OA\Get(
 *     path="/api/user",
 *     summary="Get authenticated user information",
 *     tags={"Users"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(ref="#/components/schemas/UserResource")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated"
 *     )
 * )
 *      
 * @OA\Get(
 *     path="/api/user/{id}",
 *     summary="Get user by ID",
 *     tags={"Users"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(ref="#/components/schemas/UserResource")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 *      
 * @OA\Put(
 *     path="/api/user/{id}",
 *     summary="Update user information",
 *     tags={"Users"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(property="name", type="string", example="John Doe", description="User's name"),
 *                 @OA\Property(property="email", type="string", format="email", example="john@example.com", description="User's email"),
 *                 @OA\Property(property="phone", type="string", example="1234567890", description="User's phone number"),
 *                 @OA\Property(property="address", type="string", example="123 Main St", description="User's address"),
 *                 @OA\Property(property="image", type="string", format="binary", description="User's profile image")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated successfully",
 *         @OA\JsonContent(ref="#/components/schemas/UserResource")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation error"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 */
class UserDocumentation
{
}