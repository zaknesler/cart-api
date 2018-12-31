<?php

namespace App\Models;

use App\Models\Country;
use App\Models\Traits\HasPrices;
use App\Models\Pivots\CountryShippingMethod;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasPrices;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'price',
    ];

    /**
     * A shipping method belongs to many countries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function countries()
    {
        return $this->belongsToMany(Country::class, 'country_shipping_method')
                    ->using(CountryShippingMethod::class);;
    }
}
