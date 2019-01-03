<?php

namespace App\Rules\Addresses;

use App\Models\Country;
use Illuminate\Contracts\Validation\Rule;

class ValidCountryDivision implements Rule
{
    /**
     * The country to check.
     *
     * @var \App\Models\Country
     */
    protected $country;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($countryId)
    {
        $this->country = Country::find($countryId);
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
        if (!$this->country) {
            return false;
        }

        return $this->country->divisions->contains('id', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The country division does not belong to the specified country.';
    }
}
