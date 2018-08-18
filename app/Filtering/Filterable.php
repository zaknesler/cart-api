<?php

namespace App\Filtering;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeWithFilters(Builder $builder, $filters = [])
    {
        return (new RequestFilter(request()))->apply($builder, $filters);
    }
}
