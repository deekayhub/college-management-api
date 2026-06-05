<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{

    #[OA\Post(
        path: "/api/register",
        summary: "Register User",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "email", "password", "password_confirmation"],
                properties: [
                    new OA\Property(
                        property: "name",
                        type: "string",
                        nullable: true
                    ),
                    new OA\Property(
                        property: "email",
                        type: "string",
                        format: "email",
                        nullable: true
                    ),
                    new OA\Property(
                        property: "password",
                        type: "string",
                        format: "password",
                        nullable: true
                    ),
                    new OA\Property(
                        property: "password_confirmation",
                        type: "string",
                        format: "password",
                        nullable: true
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "User registered successfully"
            ),
            new OA\Response(
                response: 422,
                description: "Validation error"
            )
        ]
    )]
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $token = $user->createToken('college-management-token')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'Rgister Successfully',
            'token' => $token,
            'data' => $user,
        ]);
    }


    #[OA\Post(
        path: "/api/login",
        tags: ["Authentication"],
        summary: "Login User",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email", "password"],
                properties: [
                    new OA\Property(
                        property: "email",
                        type: "string",
                        example: "deepak@gmail.com"
                    ),
                    new OA\Property(
                        property: "password",
                        type: "string",
                        example: "password"
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Login Successful")
        ]
    )]
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if(!Auth::attempt($validatedData)){
            return response()->json([
                'success' => false,
                'message' => 'Invalid Credentials',
            ]);
        }

        $user = Auth::user();

        $token = $user->createToken('college-management-token')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'Login Successfully',
            'token' => $token,
            'data' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logout Successfully',
        ]);
    }
}
