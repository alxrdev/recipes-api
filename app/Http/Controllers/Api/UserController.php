<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\Users\CreateUserService;
use Exception;
use Illuminate\Http\Request;

class UserController extends ApiController
{
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
     * @param CreateUserRequest  $request
     * @param CreateUserService $createUserService
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request, CreateUserService $createUserService)
    {
        try {
            $user = $createUserService->execute($request);
            return response()
                ->json([
                    'message' => 'User created successfully.',
                    'data' => new UserResource($user)
                ], 200);
        } catch (Exception $err) {
            return response()
                ->json([
                    'message' => 'Error on user creation.',
                    'errors' => [
                        'server' => $err->getMessage()
                    ]
                ], 500);
        }
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
