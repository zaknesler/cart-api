<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'card_type',
        'last_four',
        'default',
        'provider_id',
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
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($paymentMethod) {
            if ($paymentMethod->default) {
                $paymentMethod->user->paymentMethods()->update([
                    'default' => false,
                ]);
            }
        });
    }

    /**
     * Set the value of the default attribute
     *
     * @param string|bool  $value
     */
    public function setDefaultAttribute($value)
    {
        $this->attributes['default'] = (($value === 'true' || $value) ? true : false);
    }

    /**
     * A payment method belongs to a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
