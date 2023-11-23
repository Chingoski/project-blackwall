<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Http\Requests\RegisterRequest;
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
            return response()->json(['message' => 'Login failed.'], 401);
        }

        return response()->json([
            'meta' => [
                'message' => 'Success',
                'auth'    => [
                    'token' => $user->createToken("API TOKEN")->plainTextToken,
                ],
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

    public function passwordReset(PasswordResetRequest $request): JsonResponse
    {
        $data = $request->validated();


        $user = Auth::user();
        $correctPassword = $user && Hash::check($data['current_password'], $user?->password);

        if (!$correctPassword) {
            return response()->json(['message' => 'Password Reset Failed.'], 401);
        }

        $user->password = Hash::make($data['new_password']);
        $user->save();

        return response()->json([
            'meta' => [
                'message' => 'Success',
            ],
            'data' => $user->toArray(),
        ]);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $createData = $request->validated();
        $createData['password'] = Hash::make($createData['password']);

        /** @var User $user */
        $user = User::query()->create($createData);

        $authToken = $user->createToken('API_TOKEN')->plainTextToken;

        return response()->json([
            'meta' => [
                'message' => 'Sucess',
                'auth'    => [
                    'token' => $authToken,
                ],
            ],
            'data' => $user->toArray(),
        ]);
    }
}
