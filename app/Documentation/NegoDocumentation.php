<?php
namespace App\Documentation;

/**
 * @OA\Tag(
 *     name="Negotiation",
 *     description="API Endpoints for price negotiation"
 * )
 *
 * @OA\Schema(
 *     schema="Nego",
 *     required={"user_id", "product_id", "nego_price", "status"},
 *     @OA\Property(property="id", type="integer", format="int64", description="Negotiation ID"),
 *     @OA\Property(property="user_id", type="integer", description="User ID who initiated negotiation"),
 *     @OA\Property(property="product_id", type="integer", description="Product ID being negotiated"),
 *     @OA\Property(property="nego_price", type="number", format="float", description="Negotiated price"),
 *     @OA\Property(property="status", type="string", enum={"pending","accepted","rejected","cancelled"}, description="Negotiation status"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(
 *         property="user",
 *         description="User who initiated the negotiation",
 *         ref="#/components/schemas/UserResource"
 *     ),
 *     @OA\Property(
 *         property="product",
 *         description="Product being negotiated",
 *         ref="#/components/schemas/ProductResource"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/nego",
 *     operationId="requestNego",
 *     tags={"Negotiation"},
 *     summary="Request price negotiation",
 *     description="Submit a price negotiation request for a product",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"product_id", "nego_price"},
 *             @OA\Property(property="product_id", type="integer", description="Product ID"),
 *             @OA\Property(property="nego_price", type="number", format="float", minimum=0, description="Proposed negotiation price")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Negotiation request created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Nego request created successfully."),
 *             @OA\Property(property="data", ref="#/components/schemas/Nego")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Product not negotiable or validation error",
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
 *     path="/api/nego",
 *     operationId="myNegos",
 *     tags={"Negotiation"},
 *     summary="Get user's negotiations",
 *     description="Retrieve all negotiation requests made by the authenticated user",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="filter",
 *         in="query",
 *         description="Filter by status (comma-separated)",
 *         required=false,
 *         @OA\Schema(type="string", example="pending,accepted")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Negotiations retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Nego")
 *             )
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/nego/{id}",
 *     operationId="negoDetail",
 *     tags={"Negotiation"},
 *     summary="Get negotiation details",
 *     description="Retrieve details of a specific negotiation",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Negotiation ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Negotiation details retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", ref="#/components/schemas/Nego")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Negotiation not found",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/nego/cancel/{id}",
 *     operationId="cancelNego",
 *     tags={"Negotiation"},
 *     summary="Cancel negotiation",
 *     description="Cancel a negotiation request",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Negotiation ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Negotiation cancelled successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="data", ref="#/components/schemas/Nego")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Cannot cancel negotiation",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/nego-seller",
 *     operationId="sellerNegos",
 *     tags={"Negotiation"},
 *     summary="Get seller's received negotiations",
 *     description="Retrieve all negotiation requests received by the seller",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Seller negotiations retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Nego")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Seller access required",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/nego-seller/{id}",
 *     operationId="sellerNegoDetail",
 *     tags={"Negotiation"},
 *     summary="Get seller negotiation details",
 *     description="Retrieve details of a specific negotiation for seller",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Negotiation ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Seller negotiation details retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", ref="#/components/schemas/Nego")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Seller access required",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Negotiation not found",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/nego/accept/{id}",
 *     operationId="acceptNego",
 *     tags={"Negotiation"},
 *     summary="Accept negotiation",
 *     description="Accept a negotiation request (seller only)",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Negotiation ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Negotiation accepted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="data", ref="#/components/schemas/Nego")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Seller access required",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Cannot accept negotiation",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/nego/decline/{id}",
 *     operationId="declineNego",
 *     tags={"Negotiation"},
 *     summary="Decline negotiation",
 *     description="Decline a negotiation request (seller only)",
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Negotiation ID",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Negotiation declined successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="data", ref="#/components/schemas/Nego")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Seller access required",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Cannot decline negotiation",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     )
 * )
 */
class NegoDocumentation
{
    // This class is only for documentation purposes
}