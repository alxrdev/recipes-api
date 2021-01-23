<?php

namespace Tests\Feature\Services\Recipes;

use App\Exceptions\AppException;
use App\Models\Recipe;
use App\Repositories\Interfaces\IRecipesRepository;
use App\Services\Recipes\Interfaces\IFindRecipeService;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class FindRecipeServiceTest extends TestCase
{
    /** @test */
    public function should_throw_an_AppError_when_not_found_a_recipe()
    {
        $this->instance(
            IRecipesRepository::class,
            Mockery::mock(IRecipesRepository::class, function (MockInterface $mock) {
                $mock->shouldReceive('getById')
                    ->once()
                    ->andThrow(new AppException('Recipe not found.', 'Recipe not found.', 404));
            }
        ));

        $this->expectException(AppException::class);

        app(IFindRecipeService::class)->execute(1);
    }

    /** @test */
    public function should_return_a_recipe()
    {
        $this->instance(
            IRecipesRepository::class,
            Mockery::mock(IRecipesRepository::class, function (MockInterface $mock) {
                $mock->shouldReceive('getById')
                    ->once()
                    ->andReturn(new Recipe([
                        'id' => 1,
                        'user_id' => 1,
                        'image' => 'images/image.jpg',
                        'steps' => json_encode([
                            [
                                'position' => 1,
                                'image' => 'images/image123.jpg',
                                'content' => 'The step content.'
                            ],
                            [
                                'position' => 2,
                                'image' => 'images/image1234.jpg',
                                'content' => 'The step content.'
                            ],
                            [
                                'position' => 3,
                                'image' => 'images/image1235.jpg',
                                'content' => 'The step content.'
                            ]
                        ])
                    ]));
            }
        ));

        $recipe = app(IFindRecipeService::class)->execute(1);

        $this->assertInstanceOf(Recipe::class, $recipe);
    }
}