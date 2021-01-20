<?php

namespace App\Repositories\Interfaces;

use App\Models\Recipe;

interface IRecipesRepository
{
    public function getById(int $id) : Recipe;
    public function store(array $fields) : Recipe;
}