<?php

namespace App\Traits;

use App\Scoping\RequestScope;
use Illuminate\Database\Eloquent\Builder;

trait HasScopes
{
    public function scopeWithScopes(Builder $builder, $scopes = [])
    {
        return (new RequestScope(request()))->apply($builder, $scopes);
    }
}
