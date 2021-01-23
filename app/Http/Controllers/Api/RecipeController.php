<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Recipes\CreateRecipeRequest;
use App\Http\Resources\RecipeResource;
use App\Services\Recipes\Interfaces\ICreateRecipeService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateRecipeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRecipeRequest $request)
    {
        $createRecipeService = app(ICreateRecipeService::class);
        $recipe = $createRecipeService->execute($request->only([
            'user_id',
            'title',
            'description',
            'ingredients',
            'steps',
            'preparation_time',
            'difficulty'
        ]), $request->allFiles());

        return $this->success('User created successfully.', new RecipeResource($recipe), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
