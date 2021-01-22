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
    public function save(array $fields) : Recipe
    {
        try {
            $recipe = $this->model->create($fields);
            return $recipe;
        } catch (Exception $err) {
            throw new AppException($err->getMessage(), 'Error trying to save the recipe.', 500);
        }
    }

    /**
     * Method that update a recipe
     * 
     * @param array $fields The recipe fields
     * @param int $id The recipe id
     * @throws AppError
     * @return Recipe $recipe The updated recipe
     */
    public function update(array $fields, int $id) : Recipe
    {
        try {
            $recipe = $this->model->where('id', $id)->update($fields);
            return $recipe;
        } catch (Exception $err) {
            throw new AppException($err->getMessage(), 'Error trying to save the recipe.', 500);
        }
    }
}