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
 *     @OA\Property(property="status", type="string", enum={"pending","accepted","declined","cancelled"}, description="Negotiation status"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Update timestamp"),
 *     @OA\Property(
 *         property="user",
 *         description="User who initiated the negotiation",
 *         ref="#/components/schemas/UserResource"
 *     ),
 *     @OA\Property(
 *         property="product",
 *         description="Product being negotiated",
 *         ref="#/components/schemas/Product"
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
 *             @OA\Property(property="product_id", type="integer", description="Product ID to negotiate"),
 *             @OA\Property(property="nego_price", type="number", format="float", description="Proposed negotiation price")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Negotiation request created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Negotiation request submitted successfully"),
 *             @OA\Property(property="data", ref="#/components/schemas/Nego")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Validation error or product not negotiable"
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/nego",
 *     operationId="myNegos",
 *     tags={"Negotiation"},
 *     summary="Get user negotiations",
 *     description="Get all negotiations initiated by authenticated user",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Nego"))
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/nego/{id}",
 *     operationId="negoDetail",
 *     tags={"Negotiation"},
 *     summary="Get negotiation details",
 *     description="Get details of a specific negotiation",
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
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", ref="#/components/schemas/Nego")
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/nego/cancel/{id}",
 *     operationId="cancelNego",
 *     tags={"Negotiation"},
 *     summary="Cancel negotiation",
 *     description="Cancel a pending negotiation",
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
 *             @OA\Property(property="message", type="string", example="Negotiation cancelled successfully")
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/nego-seller",
 *     operationId="sellerNegos",
 *     tags={"Negotiation"},
 *     summary="Get seller negotiations",
 *     description="Get all negotiations for products owned by authenticated seller",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Nego"))
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/nego-seller/{id}",
 *     operationId="sellerNegoDetail",
 *     tags={"Negotiation"},
 *     summary="Get seller negotiation details",
 *     description="Get details of a specific negotiation for seller",
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
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", ref="#/components/schemas/Nego")
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/nego/accept/{id}",
 *     operationId="acceptNego",
 *     tags={"Negotiation"},
 *     summary="Accept negotiation",
 *     description="Accept a negotiation request as seller",
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
 *             @OA\Property(property="message", type="string", example="Negotiation accepted successfully")
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/nego/decline/{id}",
 *     operationId="declineNego",
 *     tags={"Negotiation"},
 *     summary="Decline negotiation",
 *     description="Decline a negotiation request as seller",
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
 *             @OA\Property(property="message", type="string", example="Negotiation declined successfully")
 *         )
 *     )
 * )
 */
class NegoDocumentation
{
}