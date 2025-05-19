<?php
namespace App\Documentation;
/**
 * @OA\Tag(
 *     name="OTP",
 *     description="API Endpoints for sending OTP for verification and password reset"
 * )
 
 * @OA\Post(
 *     path="/api/verify-email/send",
 *     summary="Send OTP for email verification",
 *     tags={"OTP"},
 * security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email"},
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OTP sent successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="OTP sent successfully")
 *         )
 *     ),
 * @OA\Response(
 *         response=401,
 *         description="Unauthenticated"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Failed to send OTP",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Failed to send OTP")
 *         )
 *     )
 * )
 

 * @OA\Post(
 *     path="/api/forgot-password",
 *     summary="Send OTP for password reset",
 *     tags={"OTP"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email"},
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OTP sent successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="OTP sent successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Failed to send OTP",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Failed to send OTP")
 *         )
 *     )
 * )
 */
class OtpDocumentation{}