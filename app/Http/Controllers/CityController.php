<?php

namespace App\Http\Controllers;

use App\Filters\CityFilters;
use App\Http\Requests\City\GetCitiesRequest;
use App\Http\Response\BodyDataGenerator;
use App\Http\Response\ResponseGenerator;
use App\Models\City;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Response;

class CityController extends Controller
{
    public function __construct(protected City $model, protected CityFilters $filterClass, protected ResponseGenerator $responseGenerator)
    {
    }

    /**
     * @throws AuthorizationException
     */
    public function index(GetCitiesRequest $request): Response
    {
        $this->authorize(__FUNCTION__, $this->model::class);

        $filters = $request->validated();

        $cities = City::query()
            ->applyFilters($this->filterClass->setFilters($filters))
            ->paginate(self::PAGINATION_LIMIT);

        $body = (new BodyDataGenerator($this->model->getTransformer()))->setData($cities)->generateBody();

        return $this->responseGenerator->success($body);
    }

    /**
     * @throws AuthorizationException
     */
    public function find(City $city): Response
    {
        $this->authorize(__FUNCTION__, $city);

        $body = (new BodyDataGenerator($this->model->getTransformer()))->setData($city)->generateBody();

        return $this->responseGenerator->success($body);
    }
}
