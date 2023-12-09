<?php

namespace App\Filters;

class TagFilters extends BaseFilters
{
    public function search(string $search): void
    {
        $this->builder->where('name', 'ilike', "%{$search}%");
    }
}
