<?php
namespace App\Documentation;

/**
 * @OA\Schema(
 *     schema="SuccessResponse",
 *     title="Success Response",
 *     description="Standard success response wrapper",
 *     @OA\Property(property="status", type="boolean", example=true, description="Response status"),
 *     @OA\Property(property="message", type="string", example="Operation successful", description="Response message"),
 *     @OA\Property(property="data", description="Response data", oneOf={
 *         @OA\Schema(type="object"),
 *         @OA\Schema(type="array", @OA\Items(type="object")),
 *         @OA\Schema(type="string"),
 *         @OA\Schema(type="null")
 *     })
 * )
 *
 * @OA\Schema(
 *     schema="FailResponse",
 *     title="Fail Response",
 *     description="Standard error response wrapper",
 *     @OA\Property(property="status", type="boolean", example=false, description="Response status"),
 *     @OA\Property(property="message", type="string", example="Operation failed", description="Error message"),
 *     @OA\Property(property="data", type="object", nullable=true, description="Additional error data")
 * )
 *
 * @OA\Schema(
 *     schema="ValidationErrorResponse",
 *     title="Validation Error Response",
 *     description="Validation error response",
 *     @OA\Property(property="success", type="boolean", example=false, description="Response status"),
 *     @OA\Property(property="message", type="string", example="Validation error", description="Error message"),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         description="Validation errors",
 *         @OA\AdditionalProperties(
 *             type="array",
 *             @OA\Items(type="string")
 *         )
 *     )
 * )
 */
class BaseSchemas
{
    // This class is only for documentation purposes
}