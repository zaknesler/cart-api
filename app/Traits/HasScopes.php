<?php

namespace App\Traits;

use App\Scoping\ScopeManager;
use Illuminate\Database\Eloquent\Builder;

trait HasScopes
{
    public function scopeWithScopes(Builder $builder, $scopes = [])
    {
        return (new ScopeManager(request()))->apply($builder, $scopes);
    }
}
