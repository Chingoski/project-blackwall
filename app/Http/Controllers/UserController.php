<?php

namespace App\Http\Controllers;

use App\Http\Response\ResponseGenerator;
use App\Models\User;

class UserController extends Controller
{
    public function __construct(User $user, ResponseGenerator $responseGenerator)
    {
        parent::__construct($user, $responseGenerator);
    }
}
