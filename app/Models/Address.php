<?php

namespace App\Models;

use App\Models\Traits\CanBeDefault;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use CanBeDefault;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'default',
        'name',
        'address_1',
        'address_2',
        'city',
        'postal_code',
        'country_id',
        'country_division_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'default' => 'boolean',
    ];

    /**
     * An address belongs to a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * An address belongs to a country.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * An address belongs to a country division.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function countryDivision()
    {
        return $this->belongsTo(CountryDivision::class);
    }
}
