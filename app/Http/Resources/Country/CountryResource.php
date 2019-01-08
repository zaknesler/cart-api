<?php

namespace App\Http\Resources\Country;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Country\CountryDivisionResource;

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
            'has_divisions' => $this->has_divisions,
            'division_type' => $this->when($this->has_divisions, function () {
                return $this->division_type;
            }),
            'divisions' => $this->when($this->has_divisions, function () {
                return CountryDivisionResource::collection($this->whenLoaded('divisions'));
            }),
        ];
    }
}
