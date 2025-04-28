<?php

namespace App\Http\Controllers;
use OpenApi\Annotations as OA;
abstract class Controller
{
        /** 
    * @OA\Info(
    *      version="1.0.0",
    *      title="UB Mager API Documentation",
    *      description="API Documentation for UB Mager E-commerce System",
    *      @OA\Contact(
    *          email="admin@example.com"
    *      ),
    *      @OA\License(
    *          name="Apache 2.0",
    *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
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
    *      url=L5_SWAGGER_CONST_HOST,
    *      description="API Server"
    * )
    * 
*/
    public function __construct()
    {
        // Constructor logic if needed
    }
}
