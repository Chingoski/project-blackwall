<?php

namespace App\Http\Controllers;

use App\Filters\TradeFilters;
use App\Http\Requests\Trade\GetTradesRequest;
use App\Http\Response\BodyDataGenerator;
use App\Http\Response\ResponseGenerator;
use App\LogicValidators\Trade\UserCanSearchTradesForGameListingLogicValidator;
use App\Models\GameListing;
use App\Models\Trade;
use Illuminate\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class TradeController extends Controller
{
    public function __construct(Trade $trade, protected TradeFilters $filterClass, ResponseGenerator $responseGenerator)
    {
        parent::__construct($trade, $responseGenerator);
    }

    /**
     * @throws AuthorizationException
     */
    public function index(GetTradesRequest $request): Response
    {
        $this->authorize('index', $this->model::class);

        $filters = $request->validated();

        $gameListing = GameListing::query()->findOrFail($filters['game_listing_id']);

        (new UserCanSearchTradesForGameListingLogicValidator())->validate(Auth::user(), $gameListing);
        if ($gameListing->owner_id != Auth::user()->getKey()) {
            $filters['trader_user_id'] = Auth::user()->getKey();
        }

        $models = $this->model->newQuery()
            ->applyFilters($this->filterClass->setFilters($filters))
            ->paginate(self::PAGINATION_LIMIT);

        if (isset($filters['include'])) {
            $models->load($filters['include']);
        }

        $body = (new BodyDataGenerator($this->model->getTransformer()))->setData($models)->generateBody();

        return $this->responseGenerator->success($body);
    }
}
