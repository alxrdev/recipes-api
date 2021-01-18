<?php

namespace Tests\Feature\Services\Users;

use App\Exceptions\AppException;
use App\Models\User;
use App\Repositories\Interfaces\IUsersRepository;
use App\Services\Users\Interfaces\ICreateUserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateUserServiceTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function should_create_a_new_user()
    {
        $usersRepository = app(IUsersRepository::class);
        $createUserService = app(ICreateUserService::class);
        
        $createUserService->execute([
            'name' => 'Alex Rodrigues Moreira', 
            'email' => 'user@gmail.com', 
            'password' => 'mypassword'
        ]);
        
        $createdUser = $usersRepository->getByEmail('user@gmail.com');

        $this->assertInstanceOf(User::class, $createdUser);
    }

    /**
     * @test
     */
    public function should_throw_an_AppException_when_a_user_with_the_same_email_already_exists()
    {
        $createUserService = app(ICreateUserService::class);

        $this->expectException(AppException::class);
        
        $createUserService->execute([
            'name' => 'Alex Rodrigues Moreira', 
            'email' => 'user@gmail.com', 
            'password' => 'mypassword'
        ]);

        $createUserService->execute([
            'name' => 'Alex Rodrigues Moreira', 
            'email' => 'user@gmail.com', 
            'password' => 'mypassword'
        ]);
    }
}
