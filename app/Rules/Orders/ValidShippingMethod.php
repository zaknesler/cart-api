<?php

namespace App\Rules\Orders;

use App\Models\Address;
use Illuminate\Contracts\Validation\Rule;

class ValidShippingMethod implements Rule
{
    /**
     * The address to check.
     *
     * @var int
     */
    protected $address;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($addressId)
    {
        $this->address = Address::find($addressId);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!$this->address) {
            return false;
        }

        return $this->address->country->shippingMethods->contains('id', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid shipping method.';
    }
}
