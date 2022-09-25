<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $this->credentials($request);

        if (!Auth::attempt($credentials)) {
            return response([
                'error' => 'Invalid credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }
        /** @var User $user */
        $user = Auth::user();

        $token = $user->createToken('token')->plainTextToken;

        return response([
            'token' => $token,
        ]);
    }

    protected function credentials(Request $request)
    {
        return [
            'email' => $request->email,
            'password' => $request->password
        ];
    }
}
