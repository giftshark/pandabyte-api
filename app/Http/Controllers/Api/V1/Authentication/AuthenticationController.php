<?php

namespace App\Http\Controllers\Api\V1\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Requests\V1\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (!auth()->attempt($request->validated())) {
            return response()->json(['message' => 'Invalid Credentials'], 422);
        }

        $token = auth()->user()->createToken(config('auth.token_secret'))->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => auth()->user(),
            'token_type' => 'Bearer'
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        auth()->login($user);

        $token = auth()->user()->createToken(config('auth.token_secret'))->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => auth()->user(),
            'token_type' => 'Bearer'
        ]);
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
        return response()->noContent();
    }
}
