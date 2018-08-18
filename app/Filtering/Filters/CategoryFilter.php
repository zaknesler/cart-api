<?php

namespace App\Filtering\Filters;

use App\Filtering\Filter;
use Illuminate\Database\Eloquent\Builder;

class CategoryFilter implements Filter
{
    /**
     * Apply the filter to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $builder, $value)
    {
        return $builder->whereHas('categories', function (Builder $builder) use ($value) {
            $builder->where('slug', $value);
        });
    }
}
