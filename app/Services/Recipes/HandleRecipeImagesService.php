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

    /**
     * Method that validate a uploaded image
     * 
     * @param UploadFile $file The image uploaded
     * @param string $fieldName The image field name
     * @throws AppException
     * @return true
     */
    public function validateImage(UploadedFile $file, string $fieldName): bool
    {
        if (empty($file->getClientOriginalName())) {
            return true;
        }
        
        if ($file->getSize() > 2000000) {
            throw new AppException('Invalid image size.', [$fieldName => 'The image may not be greater than 2 Megabytes.'], 422);
        }

        if (!in_array($file->getMimeType(), ['image/jpeg', 'image/jpg', 'image/png'])) {
            throw new AppException('Invalid image mime type.', [$fieldName => 'The image must be of type: jpg, jpeg, png.'], 422);
        }

        if (!in_array($file->getClientOriginalExtension(), ['jpeg', 'jpg', 'png'])) {
            throw new AppException('Invalid image extension.', [$fieldName => 'The image must be of type: jpg, jpeg, png.'], 422);
        }

        return true;
    }

    /**
     * Method that validate a uploaded image array
     * 
     * @param array $files The uploaded images
     * @throws AppException
     * @return true
     */
    public function validateImages(array $files): bool
    {
        foreach ($files as $fieldName => $file) {
            $this->validateImage($file, $fieldName);
        }

        return true;
    }
}