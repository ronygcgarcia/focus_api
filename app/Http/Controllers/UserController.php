<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::all()->load('roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users|max:64',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'password' => 'required|string|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/|confirmed',
            'role_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()
                ->json($validator->errors(), 422)
                ->header('Content-Type', 'application/json');
        }

        $request->merge(['password' => Hash::make($request->input('password'))]);

        $user = User::create($request->all());
        $user->assignRole($request->input('role_id'));

        return response()->json($user, Response::HTTP_CREATED);
    }
}
