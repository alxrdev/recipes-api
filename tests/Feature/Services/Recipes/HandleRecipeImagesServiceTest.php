<?php

namespace Tests\Feature\Services\Users;

use App\Exceptions\AppException;
use App\Services\Recipes\Interfaces\IHandleRecipeImagesService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HandleRecipeImagesServiceTest extends TestCase
{
    private $handleRecipeImagesService;
    
    public function setUp() : void
    {
        parent::setUp();
        
        Storage::fake('local');

        $this->handleRecipeImagesService = app(IHandleRecipeImagesService::class);
    }

    /** @test */
    public function should_return_the_saved_file_path()
    {
        $path = $this->handleRecipeImagesService->saveImage(UploadedFile::fake()->create('image.jpg'));
        $this->assertIsString($path);
        $this->assertNotEmpty($path);
    }

    /** @test */
    public function should_save_the_recipe_image()
    {
        $path = $this->handleRecipeImagesService->saveImage(UploadedFile::fake()->create('image.jpg'));
        Storage::disk('local')->assertExists($path);
    }

    /** @test */
    public function should_save_all_steps_images()
    {
        $steps = $this->handleRecipeImagesService->saveStepsImages(
            '[{"position":1,"image":"step1","content":"this is the step 1"},{"position":2,"image":"step2","content":"this is the step 2"}]',
            [
                'step1' => UploadedFile::fake()->create('image1.jpg'),
                'step2' => UploadedFile::fake()->create('image2.jpg'),
            ]
        );

        $steps = json_decode($steps, true);

        Storage::disk('local')->assertExists([$steps[0]['image'], $steps[1]['image']]);
    }

    /** @test */
    public function should_delete_the_saved_recipe_image()
    {
        $path = $this->handleRecipeImagesService->saveImage(UploadedFile::fake()->create('image.jpg'));

        Storage::disk('local')->assertExists($path);

        $this->handleRecipeImagesService->deleteImage($path);

        Storage::disk('local')->assertMissing($path);
    }

    /** @test */
    public function should_delete_the_image_of_all_steps()
    {
        $steps = $this->handleRecipeImagesService->saveStepsImages(
            '[{"position":1,"image":"step1","content":"this is the step 1"},{"position":2,"image":"step2","content":"this is the step 2"}]',
            [
                'step1' => UploadedFile::fake()->create('image1.jpg'),
                'step2' => UploadedFile::fake()->create('image2.jpg'),
            ]
        );

        $stepsStored = json_decode($steps, true);

        Storage::disk('local')->assertExists([$stepsStored[0]['image'], $stepsStored[1]['image']]);

        $this->handleRecipeImagesService->deleteStepsImages($steps);

        Storage::disk('local')->assertMissing([$stepsStored[0]['image'], $stepsStored[1]['image']]);
    }

    /** @test */
    public function should_throw_an_AppException_when_image_size_is_invalid()
    {
        $this->expectException(AppException::class);
        
        $this->handleRecipeImagesService->validateImages([
            'step1' => UploadedFile::fake()->create('image1.jpg', 2001, 'image/jpeg'),
            'step2' => UploadedFile::fake()->create('image2.jpg', 0, 'image/jpeg'),
        ]);
    }

    /** @test */
    public function should_throw_an_AppException_when_image_extension_is_invalid()
    {
        $this->expectException(AppException::class);
        
        $this->handleRecipeImagesService->validateImages([
            'step1' => UploadedFile::fake()->create('image1.svg', 0, 'image/jpeg'),
            'step2' => UploadedFile::fake()->create('image2.jpg', 0, 'image/jpeg'),
        ]);
    }

    /** @test */
    public function should_throw_an_AppException_when_the_mime_type_is_invalid()
    {
        $this->expectException(AppException::class);
        
        $this->handleRecipeImagesService->validateImages([
            'step1' => UploadedFile::fake()->create('image1.jpg', 2000, 'image/svg'),
            'step2' => UploadedFile::fake()->create('image2.jpg', 0, 'image/jpeg'),
        ]);
    }
    
    /** @test */
    public function should_not_throw_when_the_image_is_valid()
    {
        $result = $this->handleRecipeImagesService->validateImages([
            'step1' => UploadedFile::fake()->create('image1.jpg', 1900, 'image/jpeg'),
            'step2' => UploadedFile::fake()->create('image2.jpg', 0, 'image/jpeg'),
        ]);

        $this->assertTrue($result);
    }
}