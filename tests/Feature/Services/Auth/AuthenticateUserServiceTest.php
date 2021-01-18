<?php

namespace Tests\Feature\Services\Users;

use App\Models\User;
use App\Services\Auth\Interfaces\IAuthenticateUserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticateUserServiceTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    /**
     * @test
     */
    public function should_return_a_token()
    {
        User::factory()->create([
            'email' => 'user@gmail.com'
        ]);

        $authenticateUserService = app(IAuthenticateUserService::class);

        $token = $authenticateUserService->execute(['email' => 'user@gmail.com', 'password' => 'password']);

        $this->assertNotEmpty($token);
        $this->assertIsString($token);
    }
}
