<?php

namespace App\Scoping\Scopes;

use App\Scoping\Scope;
use Illuminate\Database\Eloquent\Builder;

class CategoryScope implements Scope
{
    /**
     * Apply the scope to the query.
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
