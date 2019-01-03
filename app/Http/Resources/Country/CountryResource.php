<?php

namespace App\Http\Resources\Country;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Country\CountryDivisionCollection;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'has_divisions' => $this->whenLoaded('divisions', function () {
                return $this->hasDivisions();
            }),
            'divisions' => new CountryDivisionCollection($this->whenLoaded('divisions')),
        ];
    }
}
