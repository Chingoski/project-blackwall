<?php

namespace App\Http\Controllers;

use App\Filters\GameFilters;
use App\Http\Requests\Game\GetGamesRequest;
use App\Http\Response\ResponseGenerator;
use App\Models\Game;
use Illuminate\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;

class GameController extends Controller
{
    public function __construct(Game $game, protected GameFilters $filterClass, ResponseGenerator $responseGenerator)
    {
        parent::__construct($game, $responseGenerator);
    }

    /**
     * @throws AuthorizationException
     */
    public function index(GetGamesRequest $request): Response
    {
        return parent::baseIndex($request);
    }
}
