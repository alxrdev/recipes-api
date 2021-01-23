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

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

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
        $recipe = app(ICreateRecipeService::class)->execute(
            array_merge(
                $request->only(['title', 'description', 'ingredients', 'steps', 'preparation_time', 'difficulty']),
                ['user_id' => $request->user()->id]
            ),
            $request->allFiles()
        );

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
