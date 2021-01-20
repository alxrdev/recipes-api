<?php

namespace App\Services\Recipes\Interfaces;

use App\Models\Recipe;

interface ICreateRecipeService
{
    public function execute(array $fields) : Recipe;
}