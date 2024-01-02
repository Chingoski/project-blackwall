<?php

namespace App\Http\Controllers;

use App\Filters\GameFilters;
use App\Filters\GameListingFilters;
use App\Http\Requests\Game\GetGamesRequest;
use App\Http\Requests\GameListing\GetGameListingsRequest;
use App\Http\Response\ResponseGenerator;
use App\Models\Game;
use App\Models\GameListing;
use Illuminate\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;

class GameListingController extends Controller
{
    public function __construct(GameListing $gameListing, protected GameListingFilters $filterClass, ResponseGenerator $responseGenerator)
    {
        parent::__construct($gameListing, $responseGenerator);
    }

    /**
     * @throws AuthorizationException
     */
    public function index(GetGameListingsRequest $request): Response
    {
        return parent::baseIndex($request);
    }
}
