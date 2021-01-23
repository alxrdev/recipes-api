<?php

namespace App\Services\Recipes;

use App\Exceptions\AppException;
use App\Models\Recipe;
use App\Repositories\Interfaces\IRecipesRepository;
use App\Services\Recipes\Interfaces\IHandleRecipeImagesService;
use App\Services\Recipes\Interfaces\IUpdateRecipeService;

class UpdateRecipeService implements IUpdateRecipeService
{
    /** @var IRecipesRepository */
    private $recipesRepository;

    /** @var IHandleRecipeImagesService */
    private $handleRecipeImagesService;

    public function __construct(IRecipesRepository $recipesRepository, IHandleRecipeImagesService $handleRecipeImagesService)
    {
        $this->recipesRepository = $recipesRepository;
        $this->handleRecipeImagesService = $handleRecipeImagesService;
    }

    /**
     * Method that create a new recipe.
     * 
     * @param array $fields The recipe fields
     * @param array $files All uploaded files
     * @return Recipe $recipe The recipe created
     */
    public function execute(array $fields, array $files = []) : Recipe
    {
        $this->handleRecipeImagesService->validateImages($files);
        
        $recipeToUpdate = $this->recipesRepository->getById($fields['id']);
        
        $fields['image'] = $this->updateRecipeImage($recipeToUpdate->image, $files);
        $fields['steps'] = $this->updateStepsData($fields['steps'], $recipeToUpdate->steps, $files);

        if (isset($fields['user_id'])) {
            unset($fields['user_id']);
        }

        try {
            $recipe = new Recipe($fields);
            
            $this->recipesRepository->update($recipe->toArray(), $fields['id']);
            
            $this->shouldDeleteOldRecipeImage($recipeToUpdate->image, $fields['image']);

            $this->handleRecipeImagesService->deleteStepsImages(
                $this->getImagesToDeleteWhenSuccess($fields['steps'], $recipeToUpdate->steps)
            );

            return $recipe;
        } catch (AppException $err) {
            $this->shouldDeleteNewRecipeImage($recipeToUpdate->image, $fields['image']);

            $this->handleRecipeImagesService->deleteStepsImages(
                $this->getImagesToDeleteWhenHasAnError($fields['steps'], $recipeToUpdate->steps)
            );

            throw $err;
        }
    }

    /**
     * Method that save the updated recipe image and return the path or the old path
     * 
     * @param string $oldImage The old image
     * @param string $files Uploaded files
     * @return string The image path
     */
    private function updateRecipeImage(string $oldImage, array $files) : string
    {
        if (!isset($files['image'])) {
            return $oldImage;
        }

        $newImage = $this->handleRecipeImagesService->saveImage($files['image']);

        return (!empty($newImage)) ? $newImage : $oldImage;
    }

    /**
     * Method that delete the old recipe image when the recipe has been updated
     * 
     * @param string $oldImage The old image
     * @param string $updatedRecipeImage The image updated
     * @return void
     */
    private function shouldDeleteOldRecipeImage($oldImage, $updatedRecipeImage)
    {
        if (!empty($updatedRecipeImage) && $updatedRecipeImage !== $oldImage) {
            $this->handleRecipeImagesService->deleteImage($oldImage);
        }
    }

    /**
     * Method that delete the updated recipe image when an error occurs
     * 
     * @param string $oldImage The old image
     * @param string $updatedRecipeImage The image updated
     * @return void
     */
    private function shouldDeleteNewRecipeImage($oldImage, $updatedRecipeImage) : void
    {
        if (!empty($updatedRecipeImage) && $updatedRecipeImage !== $oldImage) {
            $this->handleRecipeImagesService->deleteImage($updatedRecipeImage);
        }
    }

    /**
     * Method that receive the new and old steps and update the data of all steps
     * 
     * @param string $newSteps Steps sended
     * @param string $oldSteps Steps from database
     * @param array $files Uploaded images
     * @return string $updatedSteps The json with updated steps
     */
    private function updateStepsData(string $newSteps, string $oldSteps, array $files) : string
    {
        $newSteps = json_decode($newSteps, true);
        $oldSteps = json_decode($oldSteps, true);

        $updatedSteps = [];

        foreach ($newSteps as $newStep) {
            $step = [];
            $oldStep = $this->findStep($newStep['position'], $oldSteps);

            if ($oldStep !== null) {
                $step = $this->updateStepData($newStep, $oldStep, $files);
            } else {
                $step = $this->buildNewStepData($newStep, $files);
            }

            array_push($updatedSteps, $step);
        }

        return json_encode($updatedSteps);
    }

    /**
     * Method that find a step item
     * 
     * @param int $position The step position
     * @param array $steps All steps
     * @return array|null $step The step found or null
     */
    private function findStep(int $position, array $steps) :? array
    {
        foreach ($steps as $step) {
            if ($step['position'] == $position) {
                return $step;
            }
        }

        return null;
    }

    /**
     * Method that update the step data
     * 
     * @param string $newSteps Steps sended
     * @param string $oldSteps Steps from database
     * @param array $files Uploaded images
     * @return string $updatedSteps The json with updated steps
     */
    private function updateStepData(array $newStep, array $oldStep, array $files) : array
    {
        $updatedStep = [];

        $image = (isset($files[$newStep['image']]))
            ? $this->handleRecipeImagesService->saveImage($files[$newStep['image']])
            : '';

        $updatedStep['image'] = (empty($image)) ? $oldStep['image'] : $image;
        $updatedStep['content'] = $newStep['content'];
        $updatedStep['position'] = $newStep['position'];

        return $updatedStep;
    }

    /**
     * Method that build a new step
     * 
     * @param string $newSteps The step data
     * @param array $files Uploaded images
     * @return array $step The step builded
     */
    private function buildNewStepData(array $newStep, array $files) : array
    {
        $step = [
            'content' => $newStep['content'],
            'position' => $newStep['position'],
            'image' => (isset($files[$newStep['image']]))
                ? $this->handleRecipeImagesService->saveImage($files[$newStep['image']])
                : ''
        ];

        return $step;
    }

    /**
     * Method that return the steps images to delete when success
     * 
     * @param string $newSteps The steps data
     * @param string $oldSteps The old steps
     * @return string $imagesToDelete The steps image to delete
     */
    private function getImagesToDeleteWhenSuccess(string $newSteps, string $oldSteps) : string
    {
        $newSteps = json_decode($newSteps, true);
        $oldSteps = json_decode($oldSteps, true);

        $imagesToDelete = [];

        foreach ($newSteps as $newStep) {
            $stepImageToDelete = $this->findStep($newStep['position'], $oldSteps);

            if ($stepImageToDelete !== null && !empty($newStep['image']) && ($newStep['image'] !== $stepImageToDelete['image'])) {
                array_push($imagesToDelete, ['image' => $stepImageToDelete['image']]);
            }
        }

        return json_encode($imagesToDelete);
    }

    /**
     * Method that return the steps images to delete when has an error
     * 
     * @param string $newSteps The steps data
     * @param string $oldSteps The old steps
     * @return string $imagesToDelete The steps image to delete
     */
    private function getImagesToDeleteWhenHasAnError(string $newSteps, string $oldSteps) : string
    {
        $newSteps = json_decode($newSteps, true);
        $oldSteps = json_decode($oldSteps, true);

        $imagesToDelete = [];

        foreach ($newSteps as $newStep) {
            $stepFound = $this->findStep($newStep['position'], $oldSteps);

            if ($stepFound !== null && !empty($newStep['image']) && ($newStep['image'] !== $stepFound['image'])) {
                array_push($imagesToDelete, ['image' => $newStep['image']]);
            }

            if ($stepFound == null && !empty($newStep['image'])) {
                array_push($imagesToDelete, ['image' => $newStep['image']]);
            }
        }

        return json_encode($imagesToDelete);
    }
}
