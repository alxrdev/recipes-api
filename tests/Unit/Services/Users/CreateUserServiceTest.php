<?php

namespace Tests\Unit\Services\Users;

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
    public function should_return_the_created_user()
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
}
