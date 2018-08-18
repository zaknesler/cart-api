<?php

namespace App\Filtering;

use Illuminate\Database\Eloquent\Builder;

interface Filter
{
    /**
     * Apply the filter to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $builder, $value);
}
