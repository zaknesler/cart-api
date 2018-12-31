<?php

namespace Tests\Feature\Countries;

use Tests\TestCase;
use App\Models\Country;
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
}
