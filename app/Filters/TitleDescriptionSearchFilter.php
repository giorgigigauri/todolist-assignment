<?php

namespace App\Filters;

use Illuminate\Support\Str;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class TitleDescriptionSearchFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $value = Str::replace(' ', '%', $value);
        $value = "%$value%";
        return $query->whereLike('title', $value)
        ->orWhereLike('description', $value);
    }
}
