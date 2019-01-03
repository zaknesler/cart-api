<?php

namespace App\Http\Controllers\Countries;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        return CountryResource::collection(
            Country::with('divisions.type')->get()
        );
    }
}
