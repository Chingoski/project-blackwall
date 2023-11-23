<?php

namespace App\Http\Response;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\TransformerAbstract;
use Spatie\Fractal\Fractal;
use Spatie\Fractalistic\Fractal as FractalModel;

class BodyDataGenerator
{
    protected Collection|array|LengthAwarePaginator|BaseModel|null $data;

    protected ?array $authBearerToken;

    protected ?array $metaData;

    public function __construct(protected TransformerAbstract $transformer, Collection|LengthAwarePaginator|array|null $data = null, array $authBearerToken = null, array $metaData = null)
    {
        $this->data = $data;
        $this->authBearerToken = $authBearerToken;
        $this->metaData = $metaData;
    }

    public function setData(BaseModel|Collection|LengthAwarePaginator|array|null $data): BodyDataGenerator
    {
        $this->data = $data;
        return $this;
    }

    public function setAuthBearerToken(array $authBearerToken): BodyDataGenerator
    {
        $this->authBearerToken = $authBearerToken;
        return $this;
    }

    public function setMetaData(array $metaData): BodyDataGenerator
    {
        $this->metaData = $metaData;
        return $this;
    }

    protected function generateMeta(): ?array
    {
        if (!isset($this->metaData) && !isset($this->authBearerToken)) {
            return null;
        }

        $meta = [];

        if (isset($this->authBearerToken)) {
            $meta['auth'] = [['token' => $this->authBearerToken]];
        }

        return [
            ...$meta,
            ...$this->metaData,
        ];
    }

    public function generateBody(): Collection|FractalModel|LengthAwarePaginator|array|null
    {
        if (!($this->data instanceof Collection || $this->data instanceof LengthAwarePaginator || $this->data instanceof BaseModel)) {
            return $this->data;
        }

        $body = Fractal::create();

        $body = ($this->data instanceof BaseModel) ? $body->item($this->data, $this->transformer) :
            $body->collection($this->data, $this->transformer);

        $body = $body
            ->addMeta($this->generateMeta());

        if ($this->data instanceof LengthAwarePaginator) {
            $body->paginateWith(new IlluminatePaginatorAdapter($this->data));
        }

        return $body->toArray();
    }
}
