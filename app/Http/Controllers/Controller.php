<?php

namespace App\Http\Controllers;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "College Management API",
    description: "College Management API documentation"
)]
#[OA\Server(
    url: "http://localhost:8000",
    description: "Local Development Server"
)]

#[OA\SecurityScheme(
    securityScheme: "sanctum",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT",
    description: "Enter your Sanctum token in the format: Bearer <token>"
)]

abstract class Controller
{
    //
}
