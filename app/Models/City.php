<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'name',
        'mayor_name',
        'address',
        'phone',
        'fax',
        'email',
        'website',
        'coat_of_arms_path',
        'latitude',
        'longitude',
        'source_url',
    ];
}
