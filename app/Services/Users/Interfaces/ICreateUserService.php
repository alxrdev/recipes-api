<?php

namespace App\Services\Users\Interfaces;

use App\Models\User;

interface ICreateUserService
{
    public function execute(array $fields) : User;
}