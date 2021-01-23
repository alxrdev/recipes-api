<?php

namespace App\Services\Recipes\Interfaces;

use App\Models\Recipe;

interface IFindRecipeService
{
    public function execute(int $id) : Recipe;
}