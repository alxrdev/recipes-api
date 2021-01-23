<?php

namespace App\Services\Recipes;

use App\Models\Recipe;
use App\Repositories\Interfaces\IRecipesRepository;
use App\Services\Recipes\Interfaces\IFindRecipeService;

class FindRecipeService implements IFindRecipeService
{
    /** @var IRecipesRepository */
    private $recipesRepository;

    public function __construct(IRecipesRepository $recipesRepository)
    {
        $this->recipesRepository = $recipesRepository;
    }

    /**
     * Method that find a recipe in the database
     * 
     * @param int $id The id of that recipe
     * @throws AppException
     * @return Recipe $recipe The recipe found
     */
    public function execute(int $id): Recipe
    {
        $recipe = $this->recipesRepository->getById($id);
        return $recipe;
    }
}