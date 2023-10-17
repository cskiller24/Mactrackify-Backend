<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class AuthController extends Controller
{
    public function checkLogin(Request $request)
    {
        /** @var User $user  */
        $user = $request->user();

        if(! $user->isBrandAmbassador()) {
            return response()->json([
                'message' => 'You are not a brand ambassador'
            ], ResponseCodes::HTTP_FORBIDDEN);
        }

        return response()->json([
            'message' => 'Authenticated',
            'data' => $user
        ]);
    }

    public function login(Request $request)
    {
        if($request->user()) {
            return response()->json([
                'message' => 'You are already authenticated'
            ], ResponseCodes::HTTP_FORBIDDEN);
        }

        $request->validate([
            'email' => ['required', 'exists:users,email'],
            'password' => ['required']
        ]);

        if(! auth()->attempt($request->only(['email', 'password']))) {
            return response()->json([
                'message' => 'Email or password is not correct',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::whereEmail($request->email)->first();

        if(! $user->isBrandAmbassador()) {
            return response()->json([
                'message' => 'You are not a brand ambassador'
            ], ResponseCodes::HTTP_FORBIDDEN);
        }

        $token = $user->createToken($user->email)->plainTextToken;

        return response()->json([
            'message' => 'Successfully logged in',
            'data' => [
                'token' => $token,
                'user' => $user
            ]
        ]);
    }

    public function logout(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $user->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
