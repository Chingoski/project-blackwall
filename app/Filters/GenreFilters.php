<?php

namespace App\Filters;

class GenreFilters extends BaseFilters
{
    public function search(string $search): void
    {
        $this->builder->where('name', 'ilike', "%{$search}%");
    }
}
