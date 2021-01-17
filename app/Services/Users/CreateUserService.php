<?php

namespace App\Services\Users;

use App\Http\Requests\Users\CreateUserRequest;
use App\Models\User;
use App\Repositories\Interfaces\IUsersRepository;

class CreateUserService 
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
     * @param CreateUserRequest $request
     * @throws AppError
     * @return User $user
     */
    public function execute(CreateUserRequest $request) : User
    {
        $fields = $request->all();

        $fields['password'] = bcrypt($request->password);

        return $this->usersRepository->create($fields);
    }
}
