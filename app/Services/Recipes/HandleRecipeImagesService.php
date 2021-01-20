<?php

namespace App\Services\Recipes;

use App\Exceptions\AppException;
use App\Services\Recipes\Interfaces\IHandleRecipeImagesService;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class HandleRecipeImagesService implements IHandleRecipeImagesService
{
    /**
     * Method that save all images from recipe's steps
     * 
     * @param string $steps The json with all steps
     * @param UploadedFile[] $files The all uploaded images
     * @throws AppException
     * @return string $path The stored image path
     */
    public function saveStepsImages(string $steps, array $files) : string
    {
        $steps = json_decode($steps, true);
        $newSteps = [];

        foreach ($steps as $step) {
            if (isset($step['image']) && !empty($step['image']) && isset($files[$step['image']])) {
                $step['image'] = $this->saveImage($files[$step['image']]);
            }

            array_push($newSteps, $step);
        }

        return json_encode($newSteps);
    }

    /**
     * Method that save an image from recipe
     * 
     * @param UploadedFile $file the file to store
     * @throws AppException
     * @return string $path The stored image path
     */
    public function saveImage(UploadedFile $file) : string
    {
        if (empty($file->getClientOriginalName())) {
            return '';
        }

        try {
            $path = Storage::putFile('images', $file);
            return $path;
        } catch (Exception $err) {
            throw new AppException($err->getMessage(), 'Internal server error.', 500);
        }
    }

    /**
     * Method that delete an recipe image
     * 
     * @param string $path The image path
     * @throws AppException
     * @return void
     */
    public function deleteImage(string $path): void
    {
        if (!Storage::exists($path)) {
            return;
        }

        try {
            Storage::delete($path);
        } catch (Exception $err) {
            throw new AppException($err->getMessage(), 'Internal server error.', 500);
        }
    }

    /**
     * Method that delete image from all steps
     * 
     * @param string $steps The json with all steps
     * @throws AppException
     * @return void
     */
    public function deleteStepsImages(string $steps) : void
    {
        $steps = json_decode($steps, true);

        foreach ($steps as $step) {
            if (isset($step['image']) && !empty($step['image'])) {
                $this->deleteImage($step['image']);
            }
        }
    }
}