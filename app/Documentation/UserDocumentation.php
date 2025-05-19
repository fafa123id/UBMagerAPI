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
 *     @OA\Property(property="phone", type="string", example="1234567890"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00.000000Z")
 * )

     
      * @OA\Tag(
      *     name="Users",
      *     description="API endpoints for User management operations"
      * )
      * 
      
     
      * @OA\Get(
      *     path="/api/user",
      *     summary="Get authenticated user information",
      *     tags={"Users"},
      *    security={{"bearerAuth":{}}},
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
      *         @OA\JsonContent(
      *             required={"name", "email", "phone"},
      *             @OA\Property(property="name", type="string", example="John Doe", description="User's name"),
      *             @OA\Property(property="email", type="string", format="email", example="john@example.com", description="User's email"),
      *             @OA\Property(property="phone", type="string", example="1234567890", description="User's phone number")
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