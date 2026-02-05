<?php

namespace App\Http\Controllers;

use App\Models\City;

class CityController extends Controller
{
    public function show(int $id)
    {
        $city = City::findOrFail($id);

        return view('city', [
            'city' => $city,
        ]);
    }
}
