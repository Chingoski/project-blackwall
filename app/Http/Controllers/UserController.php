<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Response\ResponseGenerator;
use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class UserController extends Controller
{
    public function __construct(User $user, ResponseGenerator $responseGenerator)
    {
        parent::__construct($user, $responseGenerator);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(int $userId, UpdateUserRequest $updateUserRequest): \Illuminate\Http\Response
    {
        /** @var BaseModel $user */
        $user = User::query()->findOrFail($userId);

        return parent::baseUpdate($user, $updateUserRequest);
    }
}
