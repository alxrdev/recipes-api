<?php

namespace App\Services\Users;

use App\Models\User;
use App\Repositories\Interfaces\IUsersRepository;
use App\Services\Users\Interfaces\ICreateUserService;

class CreateUserService implements ICreateUserService
{
    /**
     * @var IUsersRepository
     */
    private $usersRepository;

    public function __construct(IUsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    /**
     * Executes the service
     * 
     * @param array $fields User inputs
     * @throws AppError
     * @return User $user
     */
    public function execute(array $fields) : User
    {
        $fields['password'] = bcrypt($fields['password']);
        return $this->usersRepository->create($fields);
    }
}
