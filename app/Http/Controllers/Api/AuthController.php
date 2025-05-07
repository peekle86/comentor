<?php

namespace App\Http\Controllers\Api;

use App\Actions\Fortify\AttemptToAuthenticateUser;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\LogoutApiUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Fortify\Http\Requests\LoginRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="Auth API Endpoints"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user and return auth token",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="John Doe",
     *                 description="User Name"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 example="john@mail.com",
     *                 description="User Email"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 example="super-secret",
     *                 description="User Password"
     *             ),
     *             @OA\Property(
     *                 property="password_confirmation",
     *                 type="string",
     *                 example="super-secret",
     *                 description="User Password Confirmation"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful registration",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="plainTextToken",
     *                 type="string",
     *                 example="1|wLn8vrVU5g8N9u13WdC7qcT5KZk6yi7jo8lcqC5wfd07b1f2"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The email has already been taken."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={"email": {"The email has already been taken."}}
     *             )
     *         )
     *     )
     * )
     */
    public function register(Request $request, CreateNewUser $createNewUserAction)
    {
        $user = $createNewUserAction->create($request->all());

        return response()->json([
            'plainTextToken' => $user->createToken('authToken')->plainTextToken
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Log In user and return auth token",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 example="john@mail.com",
     *                 description="User Email"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 example="super-secret",
     *                 description="User Password"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful log in",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="plainTextToken",
     *                 type="string",
     *                 example="1|wLn8vrVU5g8N9u13WdC7qcT5KZk6yi7jo8lcqC5wfd07b1f2"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The provided credentials are incorrect"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={"email": {"The provided credentials are incorrect"}}
     *             )
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request, AttemptToAuthenticateUser $attemptToAuthenticateAction)
    {
        $user = $attemptToAuthenticateAction->execute($request->all());

        return response()->json([
            'plainTextToken' => $user->createToken('authToken')->plainTextToken
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout the authenticated user",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=204,
     *         description="Successfully logged out. No content returned."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized – invalid or missing token"
     *     )
     * )
     */
    public function logout(Request $request, LogoutApiUser $logoutApiUserAction)
    {
        $logoutApiUserAction->execute($request->user());

        return response()->noContent();
    }
}
