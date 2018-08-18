<?php

namespace App\Filtering;

use App\Filtering\Filter;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class RequestFilter
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
     * Apply the specified filters to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  array  $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $builder, array $filters)
    {
        foreach ($this->validFilters($filters) as $key => $filter) {
            if (!$filter instanceof Filter) {
                continue;
            }

            $filter->apply($builder, $this->request->get($key));
        }

        return $builder;
    }

    /**
     * Get only the filters that are present in the request.
     *
     * @param  array  $filters
     * @return array
     */
    protected function validFilters(array $filters)
    {
        return array_only($filters, array_keys($this->request->all()));
    }
}
