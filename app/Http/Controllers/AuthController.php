<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        /** @var ?User $user */
        $user = User::query()->where('email', '=', $credentials['email'])->first();

        $canLogin = $user && Hash::check($credentials['password'], $user->password);

        if (!$canLogin) {
            response()->json(['message' => 'Login failed.'], 401);
        }

        return response()->json([
            'meta' => [
                'message' => 'Success',
                'auth_token' => $user->createToken("API TOKEN")->plainTextToken
            ],
            'data' => $user->toArray(),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        if (Auth::check()) {

            /** @var User $user */
            $user = Auth::user();

            $user->currentAccessToken()->delete();

            return response()->json(['message' => 'Successfully logged out.'], 200);
        }

        return response()->json(['message' => 'Invalid token'], 401);
    }
}
