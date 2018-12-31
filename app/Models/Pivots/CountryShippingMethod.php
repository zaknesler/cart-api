<?php

namespace App\Models\Pivots;

use App\Models\Country;
use App\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CountryShippingMethod extends Pivot
{
    /**
     * A country-shipping method relation belongs to a country.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * A country-shipping method relation belongs to a shipping method.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }
}
