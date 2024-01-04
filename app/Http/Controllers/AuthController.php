<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Response\BodyDataGenerator;
use App\Http\Response\ResponseGenerator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;

class AuthController extends Controller
{
    public function __construct(protected ResponseGenerator $responseGenerator)
    {

    }

    public function login(LoginRequest $request): Response
    {
        $credentials = $request->validated();

        /** @var ?User $user */
        $user = User::query()->where('email', '=', $credentials['email'])->first();

        $canLogin = $user && Hash::check($credentials['password'], $user->password);

        if (!$canLogin) {
            throw new UnauthorizedException();
        }

        $responseBody = (new BodyDataGenerator($user->getTransformer()))
            ->setData($user)
            ->setAuthBearerToken($user->createToken("API TOKEN")->plainTextToken)
            ->generateBody();

        return $this->responseGenerator->success($responseBody);
    }

    public function logout(Request $request): Response
    {
        if (!Auth::check()) {
            throw new UnauthorizedException();
        }

        /** @var User $user */
        $user = Auth::user();

        $user->currentAccessToken()->delete();

        return $this->responseGenerator->noContent();
    }

    public function passwordReset(PasswordResetRequest $request): Response
    {
        $data = $request->validated();

        if (!Auth::check()) {
            throw new UnauthorizedException();
        }

        /** @var User $user */
        $user = Auth::user();
        $correctPassword = $user && Hash::check($data['current_password'], $user?->password);

        if (!$correctPassword) {
            throw new UnauthorizedException('Password Reset Failed.');
        }

        $user->password = Hash::make($data['new_password']);
        $user->save();

        $responseBody = (new BodyDataGenerator($user->getTransformer()))->setData($user)->generateBody();

        return $this->responseGenerator->success($responseBody);
    }

    public function register(RegisterRequest $request): Response
    {
        $createData = $request->validated();
        $createData['password'] = Hash::make($createData['password']);

        /** @var User $user */
        $user = User::create($createData);

        $authToken = $user->createToken('API_TOKEN')->plainTextToken;

        $responseBody = (new BodyDataGenerator($user->getTransformer()))
            ->setData($user)
            ->setAuthBearerToken($authToken)
            ->generateBody();

        return $this->responseGenerator->created($responseBody);
    }

    public function user(Request $request): Response
    {
        if (!Auth::check()) {
            throw new UnauthorizedException();
        }

        /** @var User $user */
        $user = Auth::user();

        $responseBody = (new BodyDataGenerator($user->getTransformer()))->setData($user)->generateBody();

        return $this->responseGenerator->success($responseBody);
    }
}
