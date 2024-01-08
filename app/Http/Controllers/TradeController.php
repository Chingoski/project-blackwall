<?php

namespace App\Http\Controllers;

use App\Filters\TradeFilters;
use App\Http\Requests\Trade\GetTradesRequest;
use App\Http\Response\ResponseGenerator;
use App\Models\Trade;
use Illuminate\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;

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
        return parent::baseIndex($request);
    }
}
