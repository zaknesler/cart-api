<?php

namespace App\Models\Pivots;

use App\Models\Order;
use App\Models\ProductVariation;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductVariationOrder extends Pivot
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'in_stock' => 'boolean',
    ];

    /**
     * A product variation-order pivot belongs to a product variation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function variation()
    {
        return $this->belongsTo(ProductVariation::class);
    }

    /**
     * A product variation-order pivot belongs to an order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
