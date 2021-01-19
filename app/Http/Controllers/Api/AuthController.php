<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthenticateUserRequest;
use App\Services\Auth\Interfaces\IAuthenticateUserService;
use App\Traits\ApiResponse;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Authenticate an user.
     *
     * @param AuthenticateUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function login(AuthenticateUserRequest $request)
    {
        $authenticateUserService = app(IAuthenticateUserService::class);
        $token = $authenticateUserService->execute($request->only(['email', 'password']));
        return $this->success('User authenticated.', ['access_token' => $token]);
    }
}
