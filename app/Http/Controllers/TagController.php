<?php

namespace App\Http\Controllers;

use App\Filters\GenreFilters;
use App\Filters\TagFilters;
use App\Http\Requests\Genre\GetGenresRequest;
use App\Http\Requests\Tag\GetTagsRequest;
use App\Http\Response\ResponseGenerator;
use App\Models\Genre;
use App\Models\Tag;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;

class TagController extends Controller
{
    public function __construct(Tag $tag, protected TagFilters $filterClass, ResponseGenerator $responseGenerator)
    {
        parent::__construct($tag, $responseGenerator);
    }

    /**
     * @throws AuthorizationException
     */
    public function index(GetTagsRequest $request): Response
    {
        return parent::baseIndex($request);
    }
}
