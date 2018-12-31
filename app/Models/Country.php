<?php

namespace App\Models;

use App\Models\ShippingMethod;
use App\Models\Pivots\CountryShippingMethod;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
    ];

    /**
     * A country belongs to many shipping methods.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function shippingMethods()
    {
        return $this->belongsToMany(ShippingMethod::class, 'country_shipping_method')
            ->using(CountryShippingMethod::class);
    }
}
