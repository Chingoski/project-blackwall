<?php

namespace App\Http\Controllers;

use App\Filters\GenreFilters;
use App\Http\Requests\Genre\GetGenresRequest;
use App\Http\Response\ResponseGenerator;
use App\Models\Genre;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;

class GenreController extends Controller
{
    public function __construct(Genre $genre, protected GenreFilters $filterClass, ResponseGenerator $responseGenerator)
    {
        parent::__construct($genre, $responseGenerator);
    }

    /**
     * @throws AuthorizationException
     */
    public function index(GetGenresRequest $request): Response
    {
        return parent::baseIndex($request);
    }
}
