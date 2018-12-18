<?php

namespace App\Filtering;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * A model can apply filters to its queries.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  array  $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithFilters(Builder $builder, $filters = [])
    {
        return (new RequestFilter(request()))->apply($builder, $filters);
    }
}
