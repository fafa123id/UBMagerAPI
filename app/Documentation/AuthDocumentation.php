<?php
namespace App\Documentation;
/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="API Endpoints for user authentication"
 * )
 * @OA\Post(
 *     path="/api/login",
 *     operationId="login",
 *     tags={"Authentication"},
 *     summary="Login user",
 *     description="Authenticates user and returns a token",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com", description="User email"),
 *             @OA\Property(property="password", type="string", format="password", example="password123", description="User password")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Login successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="token", type="string", example="1|laravel_sanctum_MQxyqMYMHVkH5OzJAMOFfgdv4nFhSltI4w65eUZH"),
 *             @OA\Property(property="message", type="string", example="Login Berhasil")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Invalid credentials"
 *     )
 * )
 * @OA\Post(
 *     path="/api/logout",
 *     operationId="logout",
 *     tags={"Authentication"},
 *     summary="Logout user",
 *     description="Destroys the user's current authenticated session and removes tokens",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=201,
 *         description="Logout successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Berhasil logout")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/register",
 *     summary="Register a new user",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","email","password","password_confirmation","role_id","phone"},
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password123"),
 *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123"),
 *             @OA\Property(property="role_id", type="integer", example=2),
 *             @OA\Property(property="phone", type="string", example="+6281234567890")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Register successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Register Berhasil")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The given data was invalid."),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 example={"email": {"The email has already been taken."}}
 *             )
 *         )
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/forgot-password/verify",
 *     summary="Verify OTP sent to the user's email to reset password.",
 *     operationId="verifyOtp",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "otp"},
 *             @OA\Property(property="email", type="string", example="user@example.com"),
 *             @OA\Property(property="otp", type="string", example="123456")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OTP verified and token generated.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="OTP verified"),
 *             @OA\Property(property="token", type="string", example="abcdef1234567890")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid OTP or expired OTP.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Invalid or OTP Expired")
 *         )
 *     )
 * )
 * 
 * 
 * @OA\Post(
 *     path="/api/reset-password",
 *     summary="Reset user password using the reset token.",
 *     operationId="resetPassword",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password", "password_confirmation", "token"},
 *             @OA\Property(property="email", type="string", example="user@example.com"),
 *             @OA\Property(property="password", type="string", example="newPassword123"),
 *             @OA\Property(property="password_confirmation", type="string", example="newPassword123"),
 *             @OA\Property(property="token", type="string", example="abcdef1234567890")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password successfully reset.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Password reset successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Token expired or invalid.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Token expired")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found or token not found.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="User not found")
 *         )
 *     )
 * )
 * 
 * @OA\Post(
 *     path="/api/new-password",
 *     summary="Allow authenticated user to change their own password.",
 *     operationId="newPassword",
 *  security={{"bearerAuth":{}}},
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"old_password", "password", "password_confirmation"},
 *             @OA\Property(property="old_password", type="string", example="oldPassword123"),
 *             @OA\Property(property="password", type="string", example="newPassword123"),
 *             @OA\Property(property="password_confirmation", type="string", example="newPassword123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password successfully changed.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Password reset successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid old password or token.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Invalid token")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="User not found")
 *         )
 *     )
 * )
 * 
 * @OA\Post(
 *     path="/api/verify-email",
 *     summary="Verify user's email address using OTP.",
 *     operationId="verifyEmail",
 * security={{"bearerAuth":{}}},
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "otp"},
 *             @OA\Property(property="email", type="string", example="user@example.com"),
 *             @OA\Property(property="otp", type="string", example="123456")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OTP verified and email status updated.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="OTP verified")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid OTP or expired OTP.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Invalid or OTP Expired")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="User not found")
 *         )
 *     )
 * )
 */
class AuthDocumentation{}