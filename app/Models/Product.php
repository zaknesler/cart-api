<?php

namespace App\Models;

use App\Models\Traits\HasPrices;
use App\Models\Traits\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFilters, HasPrices;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Determine if a specific product has any stock in any variation.
     *
     * @return bool
     */
    public function inStock()
    {
        return $this->stockCount() > 0;
    }

    /**
     * Get the total stock for all variations.
     *
     * @return bool
     */
    public function stockCount()
    {
        return $this->variations->sum(function ($variation) {
            return $variation->stockCount();
        });
    }

    /**
     * A product belongs to many categories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class)->using(CategoryProduct::class);
    }

    /**
     * A product has many variations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variations()
    {
        return $this->hasMany(ProductVariation::class)->orderBy('order', 'asc');
    }
}
