<?php

namespace App\Http\Controllers;

use App\Http\Response\BodyDataGenerator;
use App\Http\Response\ResponseGenerator;
use App\Models\BaseModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct(protected BaseModel $model, protected ResponseGenerator $responseGenerator)
    {
    }

    public const PAGINATION_LIMIT = 50;

    /**
     * @throws AuthorizationException
     */
    public function baseIndex(FormRequest $request): Response
    {
        $this->authorize('index', $this->model::class);

        $filters = $request->validated();

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
     * @throws AuthorizationException|AuthorizationException
     */
    public function find(int $id): Response
    {
        $model = $this->model->newQuery()->findOrFail($id);

        $this->authorize(__FUNCTION__, $model);

        $body = (new BodyDataGenerator($model->getTransformer()))->setData($model)->generateBody();

        return $this->responseGenerator->success($body);
    }
}
