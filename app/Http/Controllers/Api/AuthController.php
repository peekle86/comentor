<?php

namespace App\Http\Controllers\Api;

use App\Actions\Fortify\AttemptToAuthenticateUser;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\LogoutApiUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Fortify\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function register(Request $request, CreateNewUser $createNewUserAction)
    {
        $user = $createNewUserAction->create($request->all());

        return $user->createToken('authToken')->plainTextToken;
    }

    public function login(LoginRequest $request, AttemptToAuthenticateUser $attemptToAuthenticateAction)
    {
        $user = $attemptToAuthenticateAction->execute($request->all());

        return $user->createToken('authToken')->plainTextToken;
    }

    public function logout(Request $request, LogoutApiUser $logoutApiUserAction)
    {
        $logoutApiUserAction->execute($request->user());

        return response()->noContent();
    }
}
