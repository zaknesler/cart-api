<?php

namespace App\Http\Resources\Country;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Country\CountryDivisionResource;

class CountryDivisionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => $this->getType(),
            'data' => CountryDivisionResource::collection($this->collection),
        ];
    }

    /**
     * Get the division type from one of the elements.
     *
     * @return string
     */
    private function getType()
    {
        return optional($this->collection)->first()['type']['name'];
    }
}
