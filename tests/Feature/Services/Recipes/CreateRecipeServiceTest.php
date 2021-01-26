<?php

namespace Tests\Feature\Services\Users;

use App\Exceptions\AppException;
use App\Models\Recipe;
use App\Repositories\Interfaces\IRecipesRepository;
use App\Services\Recipes\Interfaces\ICreateRecipeService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class CreateRecipeServiceTest extends TestCase
{
    private $testData;

    public function setUp() : void
    {
        parent::setUp();

        Storage::fake('local');

        $this->testData = [
            [
                'user_id' => 1,
                'title' => 'My recipe',
                'description' => 'This is my recipe',
                'image' => 'recipe-image.jpg',
                'preparation_time' => '00:30:00',
                'ingredients' => 'ingredient1::ingredient1::ingredient3::ingredient4',
                'steps' => '[{"position":1,"image":"step1","content":"this is the step 1"},{"position":2,"image":"step2","content":"this is the step 2"}]',
                'difficulty' => 5
            ],
            [
                'image' => UploadedFile::fake()->create('image.jpg'),
                'step1' => UploadedFile::fake()->create('image1.jpg'),
                'step2' => UploadedFile::fake()->create('image2.jpg'),
            ]
        ];
    }

    /** @test */
    public function should_create_a_new_recipe()
    {
        $this->instance(
            IRecipesRepository::class,
            Mockery::mock(IRecipesRepository::class, function (MockInterface $mock) {
                $mock->shouldReceive('save')
                    ->once()
                    ->andReturn(new Recipe());
            })
        );

        $createRecipeService = app(ICreateRecipeService::class);

        $recipe = $createRecipeService->execute($this->testData[0], $this->testData[1]);

        $this->assertInstanceOf(Recipe::class, $recipe);
    }

    /** @test */
    public function should_throw_an_AppError_when_the_user_not_exists()
    {
        $this->instance(
            IRecipesRepository::class,
            Mockery::mock(IRecipesRepository::class, function (MockInterface $mock) {
                $mock->shouldReceive('save')
                    ->once()
                    ->andThrow(new AppException('User not found.', 'User not found.', 404));
            })
        );

        $createRecipeService = app(ICreateRecipeService::class);

        $this->expectException(AppException::class);

        $data = $this->testData;
        $data[0]['user_id'] = 2;

        $createRecipeService->execute($data[0], $data[1]);
    }

    /** @test */
    public function should_delete_all_images_if_an_error_occurs()
    {
        $this->instance(
            IRecipesRepository::class,
            Mockery::mock(IRecipesRepository::class, function (MockInterface $mock) {
                $mock->shouldReceive('save')
                    ->once()
                    ->andThrow(new AppException('User not found.', 'User not found.', 404));
            })
        );

        $createRecipeService = app(ICreateRecipeService::class);

        $data = $this->testData;
        $data[0]['user_id'] = 2;

        $this->expectException(AppException::class);

        try {
            $createRecipeService->execute($data[0], $data[1]);
        } catch (AppException $err) {
            $files = Storage::disk('local')->allFiles('images');

            $this->assertCount(0, $files);

            throw $err;
        }
    }
}