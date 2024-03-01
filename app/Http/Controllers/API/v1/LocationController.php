<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getAllCountries(Request $request)
    {
        $countries = Country::all(['id', 'name']);

        return response()->json(['success' => true, 'message' => 'Countries Retrieved!', 'data' => $countries], 200);
    }

    public function getStatesByCountryId(Request $request, $country_id)
    {
        $states = State::where('country_id', $country_id)->get(['id', 'name']);
        return response()->json(['success' => true, 'message' => 'States Retrieved!', 'data' => $states], 200);
    }

    public function getCitiesByState(Request $request, $state_id)
    {
        $cities = City::where('state_id', $state_id)->get(['id', 'name']);

        return response()->json(['success' => true, 'message' => 'Cities Retrieved!', 'data' => $cities], 200);
    }
}
