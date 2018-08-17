<?php

namespace App\Scoping;

use App\Scoping\Scope;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ScopeManager
{
    /**
     * The current request.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Initialize class.
     *
     * @param \Illuminate\Http\Request  $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply the specified scopes to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  array  $scopes
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $builder, array $scopes)
    {
        foreach ($this->validScopes($scopes) as $key => $scope) {
            if (!$scope instanceof Scope) {
                continue;
            }

            $scope->apply($builder, $this->request->get($key));
        }

        return $builder;
    }

    /**
     * Get only the scopes that are present in the request.
     *
     * @param  array  $scopes
     * @return array
     */
    protected function validScopes(array $scopes)
    {
        return array_only($scopes, array_keys($this->request->all()));
    }
}
