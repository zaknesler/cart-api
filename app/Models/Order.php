<?php

namespace App\Models;

use App\Cart\Money;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pivots\OrderProductVariation;

class Order extends Model
{
    const PENDING = 'pending';
    const PROCESSING = 'processing';
    const PAYMENT_FAILED = 'payment_failed';
    const COMPLETED = 'completed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'subtotal',
        'address_id',
        'shipping_method_id',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->status = self::PENDING;
        });
    }

    /**
     * Get the subtotal attribute as a money instance.
     *
     * @param  int  $subtotal
     * @return \App\Cart\money
     */
    public function getSubtotalAttribute($subtotal)
    {
        return new Money($subtotal);
    }

    /**
     * Get the total of an order, including shipping costs.
     *
     * @return \App\Cart\Money
     */
    public function total()
    {
        return $this->subtotal->add($this->shippingMethod->price);
    }

    /**
     * An order belongs to a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * An order belongs to an address.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    /**
     * An order belongs to a shipping method.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    /**
     * An order belongs to many products.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function products()
    {
        return $this->belongsToMany(ProductVariation::class, 'order_product_variation')
                    ->using(OrderProductVariation::class)
                    ->withPivot(['quantity'])
                    ->withTimestamps();
    }
}
