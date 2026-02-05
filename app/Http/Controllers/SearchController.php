<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $term = trim((string) $request->query('term', ''));

        if ($term === '') {
            return response()->json([]);
        }

        $term = mb_strtolower($term);
        $termAscii = Str::ascii($term);

        $results = City::query()
            ->get(['id', 'name'])
            ->filter(function ($city) use ($term, $termAscii) {
                $name = mb_strtolower((string) $city->name);
                if (str_contains($name, $term)) {
                    return true;
                }

                $nameAscii = mb_strtolower(Str::ascii($name));
                return str_contains($nameAscii, $termAscii);
            })
            ->sortBy('name')
            ->take(10)
            ->values();

        return response()->json($results);
    }
}
