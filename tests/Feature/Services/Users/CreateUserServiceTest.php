<?php

namespace Tests\Feature\Services\Users;

use App\Exceptions\AppException;
use App\Models\User;
use App\Repositories\Interfaces\IUsersRepository;
use App\Services\Users\Interfaces\ICreateUserService;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class CreateUserServiceTest extends TestCase
{
    /**
     * @test
     */
    public function should_create_a_new_user()
    {
        $this->instance(
            IUsersRepository::class,
            Mockery::mock(IUsersRepository::class, function (MockInterface $mock) {
                $mock->shouldReceive('create')
                    ->once()
                    ->andReturn(new User());
            }
        ));

        $createUserService = app(ICreateUserService::class);
        
        $user = $createUserService->execute([
            'name' => 'Alex Rodrigues Moreira', 
            'email' => 'user@gmail.com', 
            'password' => 'mypassword'
        ]);

        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * @test
     */
    public function should_throw_an_AppException_when_a_user_with_the_same_email_already_exists()
    {
        $this->instance(
            IUsersRepository::class,
            Mockery::mock(IUsersRepository::class, function (MockInterface $mock) {
                $mock->shouldReceive('create')
                    ->once()
                    ->andThrow(new AppException('The given data was invalid.', ['email' => 'The email has already been taken.'], 400));
            }
        ));

        $createUserService = app(ICreateUserService::class);

        $this->expectException(AppException::class);
        
        $createUserService->execute([
            'name' => 'Alex Rodrigues Moreira', 
            'email' => 'user@gmail.com', 
            'password' => 'mypassword'
        ]);
    }
}
