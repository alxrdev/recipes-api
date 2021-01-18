<?php

namespace App\Services\Auth\Interfaces;

interface IAuthenticateUserService
{
    public function execute(array $fields) : string;
}
