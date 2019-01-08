<?php

namespace App\Models;

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
        'has_divsions',
        'division_type',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'has_divisions' => 'boolean',
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

    /**
     * A country has many divisions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function divisions()
    {
        return $this->hasMany(CountryDivision::class);
    }
}
