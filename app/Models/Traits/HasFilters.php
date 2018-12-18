<?php

namespace App\Models\Traits;

use App\Filtering\RequestFilter;
use Illuminate\Database\Eloquent\Builder;

trait HasFilters
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
