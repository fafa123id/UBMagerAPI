<?php
namespace App\Documentation;

/**
 * @OA\Tag(
 *     name="User",
 *     description="API Endpoints for user management"
 * )
 *
 * @OA\Schema(
 *     schema="UserResource",
 *     title="User Resource",
 *     description="User resource representation",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="phone", type="string", example="1234567890"),
 *     @OA\Property(property="address", type="string", nullable=true, example="123 Main St"),
 *     @OA\Property(property="image", type="string", nullable=true, example="https://example.com/image.jpg"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="rating", type="number", format="float", nullable=true, description="Average rating for sellers only")
 * )
 *
 * @OA\Get(
 *     path="/api/user",
 *     operationId="getCurrentUser",
 *     tags={"User"},
 *     summary="Get authenticated user profile",
 *     description="Retrieve the profile of the authenticated user",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="User profile retrieved successfully",
 *         @OA\JsonContent(ref="#/components/schemas/UserResource")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/user/{id}",
 *     operationId="getUserById",
 *     tags={"User"},
 *     summary="Get user by ID",
 *     description="Retrieve a specific user by their ID",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="User ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User retrieved successfully",
 *         @OA\JsonContent(ref="#/components/schemas/UserResource")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     )
 * )
 *
 * @OA\Put(
 *     path="/api/user/{id}",
 *     operationId="updateUser",
 *     tags={"User"},
 *     summary="Update user profile",
 *     description="Update the authenticated user's profile information",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="User ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(property="name", type="string", description="User name"),
 *                 @OA\Property(property="email", type="string", format="email", description="User email (will set status to unverified)"),
 *                 @OA\Property(property="phone", type="string", description="User phone number"),
 *                 @OA\Property(property="address", type="string", description="User address"),
 *                 @OA\Property(property="image", type="string", format="binary", description="User profile image")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated successfully",
 *         @OA\JsonContent(ref="#/components/schemas/UserResource")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     )
 * )
 */
class UserDocumentation
{
    // This class is only for documentation purposes
}