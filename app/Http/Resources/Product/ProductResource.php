<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Product\ProductVariationResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'variations' => ProductVariationResource::collection(
                $this->whenLoaded('variations')->groupBy('type.name')
            ),
            'price' => $this->formattedPrice,
        ]);
    }
}
