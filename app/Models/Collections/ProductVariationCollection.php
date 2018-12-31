<?php

namespace App\Models\Collections;

use Illuminate\Database\Eloquent\Collection;

class ProductVariationCollection extends Collection
{
    /**
     * Get the products in the proper format to be synced.
     *
     * @return array
     */
    public function forSyncing()
    {
        return $this->keyBy('id')
            ->map(function ($product) {
                return [
                    'quantity' => $product->pivot->quantity,
                ];
            })->toArray();
    }
}
