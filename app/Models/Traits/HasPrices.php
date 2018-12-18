<?php

namespace App\Models\Traits;

use App\Cart\Money;

trait HasPrices
{
    /**
     * Get the raw price of the product in cents.
     *
     * @param  int $value
     * @return \Money\Money
     */
    public function getPriceAttribute($value)
    {
        return new Money($value);
    }

    /**
     * Get the price formatted with its currency.
     *
     * @return string
     */
    public function getFormattedPriceAttribute()
    {
        return $this->price->formatted();
    }
}
