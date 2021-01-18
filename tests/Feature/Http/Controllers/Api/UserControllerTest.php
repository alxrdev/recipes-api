<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;
    
    /**
     * @test
     */
    public function should_return_a_json_with_the_created_user()
    {
        $response = $this->postJson('/api/users', [
            'name' => 'Alex Rodrigues Moreira', 
            'email' => 'user@gmail.com', 
            'password' => 'mypassword',
            'password_confirmation' => 'mypassword'
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'name',
                'email'
            ]
        ]);
    }

    /**
     * @test
     */
    public function should_return_a_json_with_status_400_when_a_user_with_same_email_already_exists()
    {
        $this->postJson('/api/users', [
            'name' => 'Alex Rodrigues Moreira', 
            'email' => 'user@gmail.com', 
            'password' => 'mypassword',
            'password_confirmation' => 'mypassword'
        ]);

        $response = $this->postJson('/api/users', [
            'name' => 'Alex Rodrigues Moreira', 
            'email' => 'user@gmail.com', 
            'password' => 'mypassword',
            'password_confirmation' => 'mypassword'
        ]);

        $response->assertStatus(400);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'email'
            ]
        ]);
    }
}
