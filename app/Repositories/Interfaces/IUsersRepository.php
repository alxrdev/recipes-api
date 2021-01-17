<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface IUsersRepository
{
    public function create(array $fields) : User;
}
