<?php
namespace App\Documentation;
use OpenApi\Annotations as OA;

/** 
 * @OA\Info(
 *      version="1.0.0",
 *      title="UB Mager API Documentation",
 *      description="API Documentation for UB Mager E-commerce System",
 *      @OA\Contact(
 *          email="apiubmager@gmail.com"
 *      ),
 *      @OA\License(
 *          name="NGINX",
 *          url="https://nginx.org/LICENSE"
 *      )
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Login with email and password to get the authentication token",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth",
 * )
 *  @OA\Server(
 *      url="{{baseUrl}}",
 *      description="API Server"
 * )
 * 
 */
class ApiDocumentation{}


