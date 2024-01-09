<?php

namespace App\Http\Controllers;

use App\Filters\TradeFilters;
use App\Http\Requests\Trade\CreateTradeRequest;
use App\Http\Requests\Trade\GetTradesRequest;
use App\Http\Response\BodyDataGenerator;
use App\Http\Response\ResponseGenerator;
use App\LogicValidators\Trade\CheckIfGameListingIsAvailableForTradeLogicValidator;
use App\LogicValidators\Trade\CheckIfSameGameAsGameListingIsOfferedLogicValidator;
use App\LogicValidators\Trade\CheckIfUserAlreadyRequestedTradeLogicValidator;
use App\LogicValidators\Trade\UserCanSearchTradesForGameListingLogicValidator;
use App\Models\BaseModel;
use App\Models\GameListing;
use App\Models\OfferedTradeGame;
use App\Models\Trade;
use App\Models\User;
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

    /**
     * @throws AuthorizationException
     */
    public function create(CreateTradeRequest $request): Response
    {
        return parent::baseCreate($request);
    }

    public function validateCreate(array $data): void
    {
        /** @var GameListing $gameListing */
        $gameListing = GameListing::query()->find($data['game_listing_id']);
        /** @var User $user */
        $user = Auth::user();

        (new CheckIfGameListingIsAvailableForTradeLogicValidator($gameListing))->validate();
        (new CheckIfUserAlreadyRequestedTradeLogicValidator($user, $gameListing))->validate();
        (new CheckIfSameGameAsGameListingIsOfferedLogicValidator($gameListing, $data))->validate();
    }

    /** @var Trade $model */
    public function createRelations(BaseModel $model, array $data): void
    {
        if (!isset($data['games']) || empty($data['games'])) {
            return;
        }

        $createData = [];

        foreach ($data['games'] as $game) {
            $createData[] = [
                'game_id'     => $game['game_id'],
                'platform_id' => $game['platform_id'],
                'trade_id'    => $model->getKey(),
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        OfferedTradeGame::query()->insert($createData);
    }
}
