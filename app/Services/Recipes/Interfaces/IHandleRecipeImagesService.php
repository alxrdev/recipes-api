<?php

namespace App\Services\Recipes\Interfaces;

use Illuminate\Http\UploadedFile;

interface IHandleRecipeImagesService
{
    public function saveStepsImages(string $stepsJson, array $files) : string;
    public function saveImage(UploadedFile $file) : string;
    public function deleteStepsImages(string $stepsJson) : void;
    public function deleteImage(string $path) : void;
}