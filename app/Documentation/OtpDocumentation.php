<?php

namespace App\Documentation;

/**
 * @OA\Tag(
 *     name="OTP",
 *     description="API Endpoints for sending OTP for verification and password reset"
 * )
 */
class OtpDocumentation {}

/**
 * @OA\Post(
 *     path="/api/verify-email/send",
 *     operationId="sendEmailVerificationOtp",
 *     summary="Send OTP for email verification",
 *     tags={"OTP"},
 *     security={{"bearerAuth":{}}},
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
 *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=429,
 *         description="Too many OTP requests - throttling active",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Please wait before requesting another OTP")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Failed to send OTP",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     )
 * )
 */

/**
 * @OA\Post(
 *     path="/api/forgot-password",
 *     operationId="sendPasswordResetOtp",
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
 *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=429,
 *         description="Too many OTP requests - throttling active",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Please wait before requesting another OTP")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Failed to send OTP",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     )
 * )
 */
