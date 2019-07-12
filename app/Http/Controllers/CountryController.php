<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Filtering\Filters\CountryFilter;
use App\Http\Resources\Country\CountryResource;

class CountryController extends Controller
{
    /**
     * Fetch all of the countries.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $countries = Country::with('divisions')
            ->withFilters([
                'has_shipping_methods' => new CountryFilter(),
            ])
            ->get();

        return CountryResource::collection($countries);
    }
}
