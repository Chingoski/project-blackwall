<?php

namespace App\Http\Controllers;

use App\Filters\GameListingFilters;
use App\Http\Requests\GameListing\CreateGameListingsRequest;
use App\Http\Requests\GameListing\GetGameListingsRequest;
use App\Http\Requests\GameListing\UpdateGameListingsRequest;
use App\Http\Response\ResponseGenerator;
use App\LogicValidators\GameListing\GameListingPlatformIsCorrectLogicValidator;
use App\Models\BaseModel;
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

    /**
     * @throws AuthorizationException
     */
    public function create(CreateGameListingsRequest $request): Response
    {
        return parent::baseCreate($request);
    }

    public function validateCreate(array $data): void
    {
        (new GameListingPlatformIsCorrectLogicValidator())->validateCreate($data);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(int $id, UpdateGameListingsRequest $request): Response
    {
        /** @var BaseModel $gameListing */
        $gameListing = GameListing::query()->find($id);

        return parent::baseUpdate($gameListing, $request);
    }
}
