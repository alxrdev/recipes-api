<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\Users\CreateUserService;
use App\Services\Users\Interfaces\ICreateUserService;
use Exception;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * @var ICreateUserService
     */
    private $createUserService;

    public function __construct(ICreateUserService $createUserService)
    {
        $this->createUserService = $createUserService;
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
     * @param CreateUserRequest  $request
     * @param CreateUserService $createUserService
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        try {
            $user = $this->createUserService->execute($request->all());
            return $this->success('User created successfully.', new UserResource($user), 201);
        } catch (Exception $err) {
            return $this->failure('Error on user creation.', $err->getMessage(), 500);
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
