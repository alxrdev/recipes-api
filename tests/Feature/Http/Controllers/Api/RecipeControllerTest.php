<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RecipeControllerTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        Storage::fake('local');

        User::factory()->create();
    }

    /** @test */
    public function should_fail_when_trying_to_create_a_recipe_with_an_invalid_data()
    {
        $response = $this->post(
            '/api/recipes',
            [
                'user_id' => 1,
                'title' => 'My recipe',
                'description' => 'This is my recipe',
                'preparation_time' => '00:30:68',
                'ingredients' => 'ingredient1::ingredient1::ingredient3::ingredient4',
                'steps' => '[{"position":"","content":"this is the step 1"},{"position":2,"image":"step2","content":"this is the step 2"}]',
                'difficulty' => 5,
                'image' => UploadedFile::fake()->create('recipe.jpg', 0, 'image/gif'),
                'step1' => UploadedFile::fake()->create('step1.jpg', 0, 'image/gif'),
                'step2' => UploadedFile::fake()->create('step2.jpg', 0, 'image/jpeg')
            ],
            [
                'Content-Type' => 'multipart/form-data',
                'Accept' => 'application/json'
            ]
        );

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'steps'
            ]
        ]);
    }

    /** @test */
    public function should_create_a_new_recipe()
    {
        $response = $this->post(
            '/api/recipes',
            [
                'user_id' => 1,
                'title' => 'My recipe',
                'description' => 'This is my recipe',
                'preparation_time' => '00:30:00',
                'ingredients' => 'ingredient1::ingredient1::ingredient3::ingredient4',
                'steps' => '[{"position":1,"image":"step1","content":"this is the step 1"},{"position":2,"image":"step2","content":"this is the step 2"}]',
                'difficulty' => 5,
                'image' => UploadedFile::fake()->create('recipe.jpg', 0, 'image/jpeg'),
                'step1' => UploadedFile::fake()->create('step1.jpg', 0, 'image/jpeg'),
                'step2' => UploadedFile::fake()->create('step2.jpg', 0, 'image/jpeg')
            ],
            [
                'Content-Type' => 'multipart/form-data',
                'Accept' => 'application/json'
            ]
        );

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'user_id',
                'title',
                'description',
                'ingredients',
                'steps',
                'preparation_time',
                'difficulty',
                'created_at',
                'updated_at'
            ]
        ]);
    }
}