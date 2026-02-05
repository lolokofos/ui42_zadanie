<?php

namespace App\Http\Controllers;

use App\Models\City;

class HomeController extends Controller
{
    public function index()
    {
        $cities = City::orderBy('name')->limit(20)->get();

        return view('home', [
            'cities' => $cities,
        ]);
    }
}
