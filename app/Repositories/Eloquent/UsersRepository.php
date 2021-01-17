<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\IUsersRepository;

class UsersRepository implements IUsersRepository
{
    /**
     * @var User $user
     */
    private User $user;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

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
