<?php

namespace App\Filters;

class CityFilters extends BaseFilters
{
    public function before(): void
    {
        $this->builder->orderBy('name');
    }

    public function search(string $search): void
    {
        $this->builder->where('name', 'ilike', "%{$search}%");
    }
}
