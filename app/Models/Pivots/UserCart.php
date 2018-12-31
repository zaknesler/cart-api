<?php

namespace App\Models\Pivots;

use App\Models\User;
use App\Models\ProductVariation;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserCart extends Pivot
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity',
    ];

    /**
     * A user-cart pivot belongs to a product variation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function variation()
    {
        return $this->belongsTo(ProductVariation::class);
    }

    /**
     * A user-cart pivot belongs to a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
