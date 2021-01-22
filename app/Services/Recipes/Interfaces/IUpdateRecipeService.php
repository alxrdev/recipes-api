<?php

namespace App\Services\Recipes\Interfaces;

use App\Models\Recipe;

interface IUpdateRecipeService
{
    public function execute(array $fields, array $files = []) : Recipe;
}