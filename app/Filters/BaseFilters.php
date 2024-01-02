<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class BaseFilters
{
    public function __construct(public ?Builder $builder = null, protected array $filters = [])
    {

    }

    public function before(): void
    {
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function setFilters(array $filters): static
    {
        $this->filters = $filters;
        return $this;
    }

    public function setBuilder(?Builder $builder): static
    {
        $this->builder = $builder;
        return $this;
    }


    public function applyFilters(): Builder
    {
        $this->before();

        if (empty($this->filters)) {
            return $this->builder;
        }

        foreach ($this->filters as $filter => $value) {
            $camelCaseName = Str::camel($filter);

            match (true) {
                method_exists($this, $camelCaseName) => $this->{$camelCaseName}($value),
                method_exists($this, $filter) => $this->{$filter}($value),
                default => null,
            };
        }

        return $this->builder;
    }
}
