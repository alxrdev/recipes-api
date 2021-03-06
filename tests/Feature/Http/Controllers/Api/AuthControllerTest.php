<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp() : void
    {
        parent::setUp();

        User::factory()->create(['email' => 'user@gmail.com']);
    }

    /**
     * @test
     */
    public function should_return_a_json_with_the_access_token()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'user@gmail.com', 
            'password' => 'password'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'access_token'
            ]
        ]);
    }

    /**
     * @test
     */
    public function should_return_a_json_error_with_status_400_when_email_or_password_not_match()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'user@gmail.com', 
            'password' => 'invalid'
        ]);

        $response->assertStatus(401);
        $response->assertJsonStructure([
            'message',
            'errors'
        ]);
    }
}
