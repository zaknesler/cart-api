<?php

namespace App\Filtering\Filters;

use App\Filtering\Filter;
use Illuminate\Database\Eloquent\Builder;

class CountryFilter implements Filter
{
    /**
     * Apply the filter to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  boolean  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $builder, $value)
    {
        return $builder->has('shippingMethods');
    }
}
