<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Country;
use App\Models\CountryDivision;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CountryDivisionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_country_division_belongs_to_a_country()
    {
        $division = factory(CountryDivision::class)->create();

        $this->assertInstanceOf(Country::class, $division->country);
    }
}
