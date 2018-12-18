<?php

namespace App\Models;

use App\Cart\Money;
use App\Models\Traits\HasPrices;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasPrices;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'order',
        'price',
    ];

    /**
     * Get the raw price of the product in cents.
     *
     * @param  int $value
     * @return \Money\Money
     */
    public function getPriceAttribute($value)
    {
        if (is_null($value)) {
            return $this->product->price;
        }

        return new Money($value);
    }

    /**
     * Determine if a product variation's price differs from its parent product.
     *
     * @return boolean
     */
    public function priceVaries()
    {
        return $this->price->amount() !== $this->product->price->amount();
    }

    /**
     * A product variation belongs to a product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * A product variation has a type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function type()
    {
        return $this->hasOne(ProductVariationType::class, 'id', 'product_variation_type_id');
    }
}
