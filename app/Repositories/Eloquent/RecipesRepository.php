<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\AppException;
use App\Models\Recipe;
use App\Repositories\Interfaces\IRecipesRepository;
use Exception;

class RecipesRepository implements IRecipesRepository
{
    /**
     * @var Recipe
     */
    private $model;

    public function __construct(Recipe $model)
    {
        $this->model = $model;
    }

    /**
     * Method that finds a recipe by id.
     * 
     * @param int $id The recipe id
     * @throws AppError
     * @return $recipe The recipe
     */
    public function getById(int $id) : Recipe
    {
        $recipe = $this->model->find($id);

        if ($recipe == null) {
            throw new AppException('Recipe not found.', 'Recipe not found.', 404);
        }

        return $recipe;
    }

    /**
     * Method that stores a new recipe.
     * 
     * @param array $fields The recipe fields
     * @throws AppError
     * @return $recipe The recipe
     */
    public function store(array $fields) : Recipe
    {
        try {
            $recipe = $this->model->create($fields);
            return $recipe;
        } catch (Exception $err) {
            throw new AppException('Error trying to save the recipe.', $err->getMessage(), 500);
        }
    }
}