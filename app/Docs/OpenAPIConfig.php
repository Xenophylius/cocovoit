<?php

namespace App\Docs;

use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="API Documentation",
 *         version="1.0.0"
 *     ),
 *     @OA\Server(
 *         url="http://127.0.0.1:8000/api",
 *         description="API server"
 *     ),
 *     @OA\Components(
 *         @OA\SecurityScheme(
 *             securityScheme="sanctum",
 *             type="http",
 *             scheme="bearer",
 *             description="Bearer token authentication"
 *         )
 *     )
 * )
 */
class OpenAPIConfig {}
