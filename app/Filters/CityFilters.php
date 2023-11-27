<?php

namespace App\Filters;

class CityFilters extends BaseFilters
{
    public function search(string $search): void
    {
        $this->builder->where('name', 'ilike', "%{$search}%");
    }
}
