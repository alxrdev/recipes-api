<?php

namespace Tests\Feature\Services\Users;

use App\Exceptions\AppException;
use App\Models\User;
use App\Services\Auth\Interfaces\IAuthenticateUserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AuthenticateUserServiceTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp() : void
    {
        parent::setUp();

        User::factory()->create([
            'email' => 'user@gmail.com'
        ]);
    }

    /**
     * @test
     */
    public function should_return_a_token()
    {
        $authenticateUserService = app(IAuthenticateUserService::class);

        $token = $authenticateUserService->execute(['email' => 'user@gmail.com', 'password' => 'password']);

        $this->assertNotEmpty($token);
        $this->assertIsString($token);
    }

    /**
     * @test
     */
    public function should_throw_an_AppException_if_the_email_and_password_do_not_match()
    {
        $authenticateUserService = app(IAuthenticateUserService::class);

        $this->expectException(AppException::class);
        
        $authenticateUserService->execute(['email' => 'user@gmail.com', 'password' => 'invalid']);
    }
}
