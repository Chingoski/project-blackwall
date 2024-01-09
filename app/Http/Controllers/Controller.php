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
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

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

    public function validateCreate(array $data): void
    {
    }

    public function createRelations(BaseModel $model, array $data)
    {
    }

    public function afterCreateEventsAndJobs(BaseModel $model)
    {
    }

    /**
     * @throws AuthorizationException
     */
    public function baseCreate(FormRequest $request): Response
    {
        $this->authorize('create', $this->model::class);

        $createData = $request->validated();

        $this->validateCreate($createData);

        try {
            DB::beginTransaction();
            $model = $this->model->newQuery()
                ->create($createData)
                ->refresh();

            $this->createRelations($model, $createData);
            DB::commit();
        } catch (Throwable) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException('Crating the trade has failed.');
        }
        /** @var BaseModel $model */

        $this->afterCreateEventsAndJobs($model);

        $body = (new BodyDataGenerator($this->model->getTransformer()))->setData($model)->generateBody();

        return $this->responseGenerator->created($body);
    }

    public function validateUpdate(BaseModel $model, array $data): void
    {
    }

    /**
     * @throws AuthorizationException
     */
    public function baseUpdate(BaseModel $model, FormRequest $request): Response
    {
        $this->authorize('update', $model);

        $updateData = $request->validated();

        $this->validateUpdate($model, $updateData);

        $model->update($updateData);
        $model->refresh();

        $body = (new BodyDataGenerator($this->model->getTransformer()))->setData($model)->generateBody();

        return $this->responseGenerator->success($body);
    }

    /**
     * @throws AuthorizationException
     */
    public function simpleDelete(int $id): Response
    {
        $model = $this->model->newQuery()
            ->find($id);

        $this->authorize('delete', $model);

        $model->delete();

        return $this->responseGenerator->noContent();
    }
}
