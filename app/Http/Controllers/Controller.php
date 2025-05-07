<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *    title="Backend API",
 *    version="1.0.0",
 * ),
 *
 * @OA\PathItem(
 *     path="/api/"
 * ),
 *
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 *      description="Enter your Bearer Token"
 *  )
 */
abstract class Controller extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests;
}
