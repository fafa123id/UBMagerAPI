<?php
namespace App\Documentation;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="API Endpoints for user authentication"
 * )
 *
 * @OA\Post(
 *     path="/api/register",
 *     operationId="register",
 *     tags={"Authentication"},
 *     summary="Register a new user",
 *     description="Create a new user account",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"name", "email", "password", "password_confirmation", "phone", "address"},
 *                 @OA\Property(property="name", type="string", description="User name"),
 *                 @OA\Property(property="email", type="string", format="email", description="User email"),
 *                 @OA\Property(property="password", type="string", format="password", description="User password"),
 *                 @OA\Property(property="password_confirmation", type="string", format="password", description="Password confirmation"),
 *                 @OA\Property(property="role_id", type="integer", description="User role (0=buyer, 1=seller)", default=0),
 *                 @OA\Property(property="phone", type="string", description="User phone number"),
 *                 @OA\Property(property="address", type="string", description="User address"),
 *                 @OA\Property(property="image", type="string", format="binary", description="User profile image")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User registered successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Register Berhasil")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/login",
 *     operationId="login",
 *     tags={"Authentication"},
 *     summary="Login user",
 *     description="Authenticate user and return access token",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", format="email", description="User email"),
 *             @OA\Property(property="password", type="string", format="password", description="User password")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="token", type="string", description="Access token"),
 *             @OA\Property(property="message", type="string", example="Login Berhasil")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Invalid credentials",
 *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
 *     ),
 *     @OA\Response(
 *         response=429,
 *         description="Too many login attempts",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/logout",
 *     operationId="logout",
 *     tags={"Authentication"},
 *     summary="Logout user",
 *     description="Revoke user access token",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Logout successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Berhasil logout")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/verify-email/send",
 *     operationId="sendEmailVerificationOtp",
 *     summary="Send OTP for email verification",
 *     tags={"Authentication"},
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
 *
 * @OA\Post(
 *     path="/api/verify-email",
 *     operationId="verifyEmail",
 *     tags={"Authentication"},
 *     summary="Verify email with OTP",
 *     description="Verify user's email using OTP",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "otp"},
 *             @OA\Property(property="email", type="string", format="email", description="User email"),
 *             @OA\Property(property="otp", type="string", description="OTP code")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Email verified successfully",
 *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid or expired OTP",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/forgot-password",
 *     operationId="forgotPassword",
 *     tags={"Authentication"},
 *     summary="Send password reset OTP",
 *     description="Send OTP to user's email for password reset",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email"},
 *             @OA\Property(property="email", type="string", format="email", description="User email")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OTP sent successfully",
 *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
 *     ),
 *     @OA\Response(
 *         response=429,
 *         description="Too many requests",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/forgot-password/verify",
 *     operationId="verifyPasswordResetOtp",
 *     tags={"Authentication"},
 *     summary="Verify password reset OTP",
 *     description="Verify OTP and get reset token",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "otp"},
 *             @OA\Property(property="email", type="string", format="email", description="User email"),
 *             @OA\Property(property="otp", type="string", description="OTP code")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OTP verified, reset token provided",
 *         @OA\JsonContent(
 *             allOf={
 *                 @OA\Schema(ref="#/components/schemas/SuccessResponse"),
 *                 @OA\Schema(
 *                     @OA\Property(
 *                         property="data",
 *                         @OA\Property(property="token", type="string", description="Reset token")
 *                     )
 *                 )
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid or expired OTP",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/reset-password",
 *     operationId="resetPassword",
 *     tags={"Authentication"},
 *     summary="Reset password",
 *     description="Reset user password using reset token",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password", "password_confirmation", "token"},
 *             @OA\Property(property="email", type="string", format="email", description="User email"),
 *             @OA\Property(property="password", type="string", format="password", description="New password"),
 *             @OA\Property(property="password_confirmation", type="string", format="password", description="Password confirmation"),
 *             @OA\Property(property="token", type="string", description="Reset token")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password reset successfully",
 *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid or expired token",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/new-password",
 *     operationId="newPassword",
 *     tags={"Authentication"},
 *     summary="Change user password",
 *     description="Change the authenticated user's password by providing the old password and new password",
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"old_password", "password", "password_confirmation"},
 *             @OA\Property(property="old_password", type="string", description="Current password"),
 *             @OA\Property(property="password", type="string", format="password", description="New password (minimum 6 characters)"),
 *             @OA\Property(property="password_confirmation", type="string", format="password", description="Password confirmation")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password changed successfully",
 *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid old password",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(ref="#/components/schemas/FailResponse")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
 *     )
 * )
 */

 
class AuthDocumentation
{
    // This class is only for documentation purposes
}