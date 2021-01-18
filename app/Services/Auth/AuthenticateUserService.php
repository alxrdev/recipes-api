<?php

namespace App\Services\Auth;

use App\Exceptions\AppException;
use App\Services\Auth\Interfaces\IAuthenticateUserService;
use Illuminate\Support\Facades\Auth;

class AuthenticateUserService implements IAuthenticateUserService
{
    public function execute(array $fields): string
    {
        if (!$token = Auth::attempt($fields)) {
            throw new AppException('Unauthorized', 'Email/password invalid.', 401);
        }

        return $token;
    }
}
