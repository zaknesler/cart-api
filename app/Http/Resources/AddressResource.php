<?php

namespace App\Http\Resources;

use App\Http\Resources\Country\CountryResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Country\CountryDivisionResource;

class AddressResource extends JsonResource
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
            'name' => $this->name,
            'address_1' => $this->address_1,
            'address_2' => $this->address_2,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'country' => new CountryResource($this->country),
            'country_division' => new CountryDivisionResource($this->whenLoaded('countryDivision')),
            'default' => $this->default,
        ];
    }
}
