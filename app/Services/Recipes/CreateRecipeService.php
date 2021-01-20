<?php

namespace App\Services\Recipes;

use App\Exceptions\AppException;
use App\Models\Recipe;
use App\Repositories\Interfaces\IRecipesRepository;
use App\Services\Recipes\Interfaces\ICreateRecipeService;
use App\Services\Recipes\Interfaces\IHandleRecipeImagesService;

class CreateRecipeService implements ICreateRecipeService
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
     * @return Recipe $recipe The recipe created
     */
    public function execute(array $fields, array $files = []) : Recipe
    {
        $fields['steps'] = $this->handleRecipeImagesService->saveStepsImages($fields['steps'], $files);
        $fields['image'] = $this->handleRecipeImagesService->saveImage($files['image']);

        try {
            $recipe = $this->recipesRepository->store($fields);
            return $recipe;
        } catch (AppException $err) {
            $this->handleRecipeImagesService->deleteImage($fields['image']);
            $this->handleRecipeImagesService->deleteStepsImages($fields['steps']);

            throw $err;
        }
    }
}
