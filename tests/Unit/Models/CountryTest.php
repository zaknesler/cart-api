<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Country;
use App\Models\ShippingMethod;
use App\Models\CountryDivision;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CountryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_country_has_many_shipping_methods()
    {
        $country = factory(Country::class)->create();

        $country->shippingMethods()->attach(
            factory(ShippingMethod::class)->create()
        );

        $this->assertInstanceOf(ShippingMethod::class, $country->shippingMethods()->first());
    }

    /** @test */
    function a_country_has_many_divisions()
    {
        $country = factory(Country::class)->create();

        $country->divisions()->save(
            factory(CountryDivision::class)->create()
        );

        $this->assertInstanceOf(CountryDivision::class, $country->divisions()->first());
    }
}
