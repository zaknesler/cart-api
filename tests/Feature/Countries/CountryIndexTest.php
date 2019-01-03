<?php

namespace Tests\Feature\Countries;

use Tests\TestCase;
use App\Models\Country;
use App\Models\CountryDivision;
use App\Models\CountryDivisionType;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CountryIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function countries_can_be_indexed()
    {
        factory(Country::class)->create([
            'code' => 'FK',
            'name' => 'Fake Country',
        ]);

        $response = $this->json('GET', '/api/countries');

        $response->assertJsonFragment([
            'id' => 1,
            'code' => 'FK',
            'name' => 'Fake Country',
        ]);
    }

    /** @test */
    public function countries_with_divisions_are_indexed()
    {
        factory(Country::class)->create([
            'code' => 'FK',
            'name' => 'Fake Country',
        ]);

        factory(CountryDivisionType::class)->create([
            'name' => 'Fake Type',
        ]);

        factory(CountryDivision::class)->create([
            'country_division_type_id' => 1,
            'country_id' => 1,
            'code' => 'FD',
            'name' => 'Fake Division Name',
        ]);

        $response = $this->json('GET', '/api/countries');

        $response->assertJsonFragment([
            'id' => 1,
            'code' => 'FK',
            'name' => 'Fake Country',
            'has_divisions' => true,
            'divisions' => [
                'type' => 'Fake Type',
                'data' => [
                    [
                        'id' => 1,
                        'code' => 'FD',
                        'name' => 'Fake Division Name',
                        'type' => 'Fake Type',
                    ]
                ],
            ],
        ]);
    }

    /** @test */
    public function countries_without_any_divisions_display_false_for_boolean()
    {
        factory(Country::class)->create();

        $response = $this->json('GET', '/api/countries');

        $response->assertJsonFragment([
            'has_divisions' => false,
        ]);
    }
}
