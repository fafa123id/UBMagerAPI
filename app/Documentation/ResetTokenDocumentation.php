<?php
namespace App\Documentation;
/**
 * @OA\Schema(
 *     schema="ResetToken",
 *     required={"user_id", "token", "expires_at"},
 *     @OA\Property(property="id", type="integer", format="int64", description="Reset token ID"),
 *     @OA\Property(property="user_id", type="integer", description="User ID"),
 *     @OA\Property(property="token", type="string", description="Reset token"),
 *     @OA\Property(property="expires_at", type="string", format="date-time", description="Token expiration time"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Update timestamp"),
 *     @OA\Property(
 *         property="user",
 *         description="User associated with this reset token",
 *         ref="#/components/schemas/UserResource"
 *     )
 * )
 */
class ResetTokenDocumentation
{
}