<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\IUsersRepository;

class UsersRepository implements IUsersRepository
{
    /**
     * Method that creates a new user
     * 
     * @param array $fields User fields
     * @throws AppError
     * @return User $user
     */
    public function create($fields) : User
    {
        $user = User::create($fields);
        return $user;
    }
}
