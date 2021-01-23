<?php

namespace Tests\Feature\Services\Recipes;

use App\Exceptions\AppException;
use App\Models\Recipe;
use App\Repositories\Interfaces\IRecipesRepository;
use App\Services\Recipes\Interfaces\IUpdateRecipeService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class UpdateRecipeServiceTest extends TestCase
{
    private $fakeSteps;
    private $fakeFields;
    private $fakeFiles;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->fakeSteps = [
            [
                'position' => 1,
                'image' => 'step1',
                'content' => 'The step content.'
            ],
            [
                'position' => 2,
                'image' => 'step2',
                'content' => 'The step content.'
            ],
            [
                'position' => 3,
                'image' => 'step3',
                'content' => 'The step content.'
            ]
        ];

        $this->fakeFields = [
            'id' => 1,
            'user_id' => 1,
            'steps' => json_encode($this->fakeSteps),
            'title' => 'My recipe',
            'description' => 'This is my recipe',
            'preparation_time' => '00:30:00',
            'ingredients' => 'ingredient1::ingredient1::ingredient3::ingredient4',
            'difficulty' => 5
        ];

        $this->fakeFiles = [
            'image' => UploadedFile::fake()->create('image.jpg', 0, 'image/jpeg'),
            'step1' => UploadedFile::fake()->create('image1.jpg', 0, 'image/jpeg'),
            'step2' => UploadedFile::fake()->create('image2.jpg', 0, 'image/jpeg'),
            'step3' => UploadedFile::fake()->create('image3.jpg', 0, 'image/jpeg')
        ];
    }

    public function setUp() : void
    {
        parent::setUp();

        Storage::fake('local');

        $this->instance(
            IRecipesRepository::class,
            Mockery::mock(IRecipesRepository::class, function (MockInterface $mock) {
                $recipe = new Recipe([
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
                ]);

                $mock->shouldReceive('getById')
                    ->andReturn($recipe);
                
                $mock->shouldReceive('update')
                    ->andReturn($recipe);
            }
        ));
    }

    /** @test */
    public function should_throws_an_AppException_when_the_user_is_not_the_owner_of_the_recipe()
    {
        $updateRecipeService = app(IUpdateRecipeService::class);

        $fakeFields = $this->fakeFields;
        $fakeFields['user_id'] = 2;

        $this->expectException(AppException::class);

        $updateRecipeService->execute($fakeFields, $this->fakeFiles);
    }

    /** @test */
    public function should_return_a_step_with_old_image_path_when_not_updating_the_image()
    {
        $updateRecipeService = app(IUpdateRecipeService::class);

        $fakeFiles = $this->fakeFiles;
        $fakeFiles['step1'] = UploadedFile::fake()->create('');

        $recipe = $updateRecipeService->execute($this->fakeFields, $fakeFiles);

        $steps = json_decode($recipe->steps, true);
        
        $this->assertNotEmpty($steps[0]['image']);
        $this->assertEquals($steps[0]['image'], 'images/image123.jpg');
    }

    /** @test */
    public function should_return_a_step_with_the_updated_image_path()
    {
        $updateRecipeService = app(IUpdateRecipeService::class);

        $recipe = $updateRecipeService->execute($this->fakeFields, $this->fakeFiles);

        $steps = json_decode($recipe->steps, true);

        $this->assertNotEmpty($steps[0]['image']);
        $this->assertNotEquals($steps[0]['image'], 'images/image123.jpg');
    }

    /** @test */
    public function should_delete_the_image_of_the_step_that_has_been_updated()
    {
        Storage::putFileAs('images', UploadedFile::fake()->create('image5.jpg'), 'image123.jpg');
        Storage::putFileAs('images', UploadedFile::fake()->create('image6.jpg'), 'image1234.jpg');
        Storage::putFileAs('images', UploadedFile::fake()->create('image7.jpg'), 'image1235.jpg');

        Storage::assertExists(['images/image123.jpg', 'images/image1234.jpg', 'images/image1235.jpg']);

        $updateRecipeService = app(IUpdateRecipeService::class);

        $fakeFiles = $this->fakeFiles;
        $fakeFiles['step2'] = UploadedFile::fake()->create('');
        $fakeFiles['step3'] = UploadedFile::fake()->create('');

        $updateRecipeService->execute($this->fakeFields, $fakeFiles);

        Storage::assertExists(['images/image1235.jpg', 'images/image1234.jpg']);
        Storage::assertMissing(['images/image123.jpg']);
    }

    /** @test */
    public function should_delete_the_uploaded_steps_images_when_an_error_occurs()
    {
        $this->instance(
            IRecipesRepository::class,
            Mockery::mock(IRecipesRepository::class, function (MockInterface $mock) {
                $mock->shouldReceive('getById')
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
                
                $mock->shouldReceive('update')
                    ->andThrow(new AppException('error', 'error'));
            }
        ));
        
        Storage::putFileAs('images', UploadedFile::fake()->create('image5.jpg'), 'image123.jpg');
        Storage::putFileAs('images', UploadedFile::fake()->create('image6.jpg'), 'image1234.jpg');
        Storage::putFileAs('images', UploadedFile::fake()->create('image7.jpg'), 'image1235.jpg');

        $updateRecipeService = app(IUpdateRecipeService::class);

        $fakeFiles = $this->fakeFiles;
        $fakeFiles['step2'] = UploadedFile::fake()->create('');
        $fakeFiles['step3'] = UploadedFile::fake()->create('');

        $this->expectException(AppException::class);

        try {
            $updateRecipeService->execute($this->fakeFields, $fakeFiles);
        } catch (AppException $err) {
            $files = Storage::disk('local')->allFiles('images');

            Storage::assertExists(['images/image123.jpg', 'images/image1234.jpg', 'images/image1235.jpg']);
            $this->assertLessThanOrEqual(4, count($files));

            throw $err;
        }
    }

    /** @test */
    public function should_return_a_recipe_with_the_old_image()
    {
        $updateRecipeService = app(IUpdateRecipeService::class);

        $fakeFiles = $this->fakeFiles;
        $fakeFiles['image'] = UploadedFile::fake()->create('');

        $recipe = $updateRecipeService->execute($this->fakeFields, $fakeFiles);
        
        $this->assertNotEmpty($recipe['image']);
        $this->assertEquals($recipe['image'], 'images/image.jpg');
    }

    /** @test */
    public function should_return_a_recipe_with_the_new_image()
    {
        $updateRecipeService = app(IUpdateRecipeService::class);

        $recipe = $updateRecipeService->execute($this->fakeFields, $this->fakeFiles);
        
        $this->assertNotEmpty($recipe['image']);
        $this->assertNotEquals($recipe['image'], 'images/image.jpg');
    }

    /** @test */
    public function should_delete_the_old_recipe_image()
    {
        $updateRecipeService = app(IUpdateRecipeService::class);

        $updateRecipeService->execute($this->fakeFields, $this->fakeFiles);
        
        Storage::assertMissing('image.jpg');
    }

    /** @test */
    public function should_delete_the_updated_recipe_image_when_an_error_occurs()
    {
        $this->instance(
            IRecipesRepository::class,
            Mockery::mock(IRecipesRepository::class, function (MockInterface $mock) {
                $mock->shouldReceive('getById')
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
                
                $mock->shouldReceive('update')
                    ->andThrow(new AppException('error', 'error'));
            }
        ));

        $updateRecipeService = app(IUpdateRecipeService::class);

        Storage::putFileAs('images', UploadedFile::fake()->create('image5.jpg'), 'image.jpg');
        Storage::putFileAs('images', UploadedFile::fake()->create('image5.jpg'), 'image123.jpg');
        Storage::putFileAs('images', UploadedFile::fake()->create('image6.jpg'), 'image1234.jpg');
        Storage::putFileAs('images', UploadedFile::fake()->create('image7.jpg'), 'image1235.jpg');

        $this->expectException(AppException::class);

        try {
            $updateRecipeService->execute($this->fakeFields, $this->fakeFiles);
        } catch (AppException $err) {
            $files = Storage::disk('local')->allFiles('images');

            $this->assertEquals(4, count($files));

            throw $err;
        }
    }
}