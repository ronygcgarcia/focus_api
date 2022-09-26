<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserStoreRequest;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::all()->load('roles'));
    }

    public function store(UserStoreRequest $request)
    {
        $request->merge(['password' => Hash::make($request->input('password'))]);

        $user = User::create($request->all());
        $user->assignRole($request->input('role_id'));

        return response()->json($user, Response::HTTP_CREATED);
    }

    public function show()
    {
        /**
         * @var User
         */
        $user = Auth::user();
        return new UserResource($user->load('roles'));
    }
}
