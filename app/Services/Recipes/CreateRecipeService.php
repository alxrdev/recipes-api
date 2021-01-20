<?php

namespace App\Services\Recipes;

use App\Models\Recipe;
use App\Repositories\Interfaces\IRecipesRepository;
use App\Services\Recipes\Interfaces\ICreateRecipeService;

class CreateRecipeService implements ICreateRecipeService
{
    /** @var IRecipesRepository */
    private $recipesRepository;

    public function __construct(IRecipesRepository $recipesRepository)
    {
        $this->recipesRepository = $recipesRepository;
    }

    /**
     * Method that create a new recipe.
     * 
     * @param array $fields The recipe fields
     * @return Recipe $recipe The recipe created
     */
    public function execute(array $fields) : Recipe
    {
        $recipe = $this->recipesRepository->store($fields);
        return $recipe;
    }
}
