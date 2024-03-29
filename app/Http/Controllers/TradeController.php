<?php

namespace App\Http\Controllers;

use App\Enums\TradeStatusEnum;
use App\Filters\TradeFilters;
use App\Http\Requests\Trade\CreateTradeRequest;
use App\Http\Requests\Trade\GetTradesRequest;
use App\Http\Requests\Trade\UpdateTradeRequest;
use App\Http\Response\BodyDataGenerator;
use App\Http\Response\ResponseGenerator;
use App\LogicValidators\Trade\CheckIfGameListingIsAvailableForTradeLogicValidator;
use App\LogicValidators\Trade\CheckIfSameGameAsGameListingIsOfferedLogicValidator;
use App\LogicValidators\Trade\CheckIfTradeCanBeCanceledLogicValidator;
use App\LogicValidators\Trade\CheckIfTradeCanBeUpdatedLogicValidator;
use App\LogicValidators\Trade\CheckIfTradeIsAlreadyConfirmedByUserLogicValidator;
use App\LogicValidators\Trade\CheckIfUserAlreadyRequestedTradeLogicValidator;
use App\LogicValidators\Trade\UserCanSearchTradesForGameListingLogicValidator;
use App\Models\BaseModel;
use App\Models\GameListing;
use App\Models\Trade;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

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

        if (isset($filters['game_listing_id'])) {
            $gameListing = GameListing::query()->findOrFail();

            (new UserCanSearchTradesForGameListingLogicValidator())->validate(Auth::user(), $gameListing);
            if ($gameListing->owner_id != Auth::user()->getKey()) {
                $filters['trader_user_id'] = Auth::user()->getKey();
            }
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
        $model->createOfferedGames($data);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(int $id, UpdateTradeRequest $request): Response
    {
        $trade = Trade::query()->findOrFail($id);

        return parent::baseUpdate($trade, $request);
    }

    /** @var Trade $model */
    public function validateUpdate(BaseModel $model, array $data): void
    {
        (new CheckIfTradeCanBeUpdatedLogicValidator($model))->validate();
        (new CheckIfSameGameAsGameListingIsOfferedLogicValidator($model->game_listing, $data))->validate();
    }

    /** @var Trade $model */
    public function updateRelations(BaseModel $model, array $data): void
    {
        $model->deleteOfferedGames();
        $model->createOfferedGames($data);
    }

    /**
     * @throws AuthorizationException
     */
    public function accept(int $tradeId, Request $request): Response
    {
        /** @var Trade $trade */
        $trade = Trade::query()->findOrFail($tradeId);

        $this->authorize('accept', $trade);

        try {
            DB::beginTransaction();

            $trade->update([
                'status' => TradeStatusEnum::Accepted->value,
            ]);

            Trade::query()
                ->where('game_listing_id', '=', $trade->game_listing_id)
                ->where('trade.id', '!=', $trade->getKey())
                ->delete();

            DB::commit();
        } catch (Throwable) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException('The trade accept has failed.');
        }

        $body = (new BodyDataGenerator($this->model->getTransformer()))->setData($trade)->generateBody();

        return $this->responseGenerator->success($body);
    }

    public function validateConfirm(User $authUser, Trade $trade): void
    {
        (new CheckIfTradeIsAlreadyConfirmedByUserLogicValidator($authUser, $trade))->validate();
    }

    public function resolveConfirm(User $user, Trade $trade): Trade
    {
        $trade->belongsToTrader($user) ? $trade->trader_confirmed = true
            : $trade->owner_confirmed = true;

        if ($trade->confirmed) {
            $trade->status = TradeStatusEnum::Finished->value;
        }

        return $trade;
    }

    /**
     * @throws AuthorizationException
     */
    public function confirm(int $tradeId, Request $request): Response
    {
        /** @var Trade $trade */
        $trade = Trade::query()->findOrFail($tradeId);
        /** @var User $user */
        $user = Auth::user();

        $this->authorize('confirm', $trade);

        $this->validateConfirm($user, $trade);

        $trade = $this->resolveConfirm($user, $trade);
        $trade->save();

        $body = (new BodyDataGenerator($this->model->getTransformer()))->setData($trade)->generateBody();

        return $this->responseGenerator->success($body);
    }

    public function validateCancel(Trade $trade): void
    {
        (new CheckIfTradeCanBeCanceledLogicValidator($trade))->validate();
    }

    /**
     * @throws AuthorizationException
     */
    public function cancel(int $tradeId, Request $request): Response
    {
        /** @var Trade $trade */
        $trade = Trade::query()->findOrFail($tradeId);

        $this->authorize('confirm', $trade);

        $this->validateCancel($trade);

        $trade->update([
            'owner_confirmed'  => false,
            'trader_confirmed' => false,
            'status'           => TradeStatusEnum::Canceled->value,
        ]);

        $body = (new BodyDataGenerator($this->model->getTransformer()))->setData($trade)->generateBody();

        return $this->responseGenerator->success($body);
    }
}
