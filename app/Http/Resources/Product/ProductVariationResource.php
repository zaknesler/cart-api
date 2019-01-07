<?php

namespace App\Http\Resources\Product;

use Illuminate\Support\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Product\ProductIndexResource;

class ProductVariationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->resource instanceof Collection) {
            return self::collection($this->resource);
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type->name,
            'price' => $this->formattedPrice,
            'price_varies' => $this->priceVaries(),
            'in_stock' => $this->inStock(),
            'stock_count' => $this->stockCount(),
            'product' => new ProductIndexResource($this->product),
        ];
    }
}
