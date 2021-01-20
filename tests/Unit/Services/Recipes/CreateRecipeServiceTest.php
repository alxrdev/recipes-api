<?php

namespace Tests\Unit\Services\Recipes;

use App\Models\Recipe;
use App\Repositories\Interfaces\IRecipesRepository;
use App\Services\Recipes\Interfaces\ICreateRecipeService;
use Illuminate\Http\UploadedFile;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class CreateRecipeServiceTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();

        $this->instance(
            IRecipesRepository::class,
            Mockery::mock(IRecipesRepository::class, function (MockInterface $mock) {
                $mock->shouldReceive('store')
                    ->once()
                    ->andReturn(new Recipe());
            }
        ));
    }

    /**
     * @test
     */
    public function should_return_the_created_recipe()
    {
        $createRecipeService = app(ICreateRecipeService::class);

        $recipe = $createRecipeService->execute(
        [
            'user_id' => 1,
            'title' => 'My recipe',
            'description' => 'This is my recipe',
            'preparation_time' => '00:30:00',
            'ingredients' => 'ingredient1::ingredient1::ingredient3::ingredient4',
            'steps' => '[{"position":1,"image":"step1.jpg","content":"this is the step 1"},{"position":2,"image":"step2.jpg","content":"this is the step 2"}]',
            'difficulty' => 5
        ],
        [
            'image' => UploadedFile::fake()->create('image.jpg')
        ]);

        $this->assertInstanceOf(Recipe::class, $recipe);
    }
}