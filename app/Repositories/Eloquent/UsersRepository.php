<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\ApiException;
use App\Models\User;
use App\Repositories\Interfaces\IUsersRepository;
use Exception;

class UsersRepository implements IUsersRepository
{
    /**
     * @var User $user
     */
    private User $model;

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
        try {
            $user = $this->model->create($fields);
            return $user;
        } catch (Exception $err) {
            throw new ApiException($err->getMessage(), 'We have an internal server error.');
        }
    }
}
