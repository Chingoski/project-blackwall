<?php

namespace App\Http\Controllers;

use App\Filters\CityFilters;
use App\Http\Requests\City\GetCitiesRequest;
use App\Http\Response\ResponseGenerator;
use App\Models\City;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;

class CityController extends Controller
{
    public function __construct(City $city, protected CityFilters $filterClass, ResponseGenerator $responseGenerator)
    {
        parent::__construct($city, $responseGenerator);
    }

    /**
     * @throws AuthorizationException
     */
    public function index(GetCitiesRequest $request): Response
    {
        return parent::baseIndex($request);
    }
}
