<?php

namespace App\Models;

use App\Cart\Money;
use App\Models\Traits\HasPrices;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pivots\ProductVariationOrder;

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
     * Determine if a particular product variation is in stock.
     *
     * @return boolean
     */
    public function inStock()
    {
        return $this->stockCount() > 0;
    }

    /**
     * Get the total stock for a particular product variation.
     *
     * @return int
     */
    public function stockCount()
    {
        return $this->stock->sum('pivot.stock');
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

    /**
     * A product variation has many stock blocks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    /**
     * A product variation has a stock.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function stock()
    {
        return $this->belongsToMany(ProductVariation::class, 'product_variation_stock_view')
            ->withPivot(['stock', 'in_stock'])
            ->using(ProductVariationOrder::class);
    }
}
