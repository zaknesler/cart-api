<?php

namespace App\Scoping;

use Illuminate\Database\Eloquent\Builder;

interface Scope
{
    /**
     * Apply the scope to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $builder, $value);
}
