<?php

namespace App\Models;

use App\Models\Traits\HasPrices;
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
}
