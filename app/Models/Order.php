<?php

namespace App\Models;

use App\Models\User;
use App\Models\Address;
use App\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
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
}
