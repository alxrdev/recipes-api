<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface IUsersRepository
{
    public function getByEmail(string $email) : User;
    
    public function create(array $fields) : User;
}
