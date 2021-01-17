<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\AppException;
use App\Models\User;
use App\Repositories\Interfaces\IUsersRepository;

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
     * Return an user by email
     * 
     * @param string $email User email
     * @throws AppException
     * @return User $user
     */
    public function getByEmail(string $email) : User
    {
        $user = $this->model->where('email', $email)->first();

        if ($user == null) {
            throw new AppException('Something went wrong :(', 'User not found.', 404);
        }

        return $user;
    }

    /**
     * Method that creates a new user
     * 
     * @param array $fields User fields
     * @throws AppException
     * @return User $user
     */
    public function create($fields) : User
    {
        if ($this->model->where('email', $fields['email'])->first() !== null) {
            throw new AppException('The given data was invalid.', ['email' => 'The email has already been taken.'], 400);
        }

        return $this->model->create($fields);
    }
}
