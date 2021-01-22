<?php

namespace App\Repositories\Interfaces;

use App\Models\Recipe;

interface IRecipesRepository
{
    public function getById(int $id) : Recipe;
    public function save(array $fields) : Recipe;
    public function update(array $fields, int $id) : Recipe;
}