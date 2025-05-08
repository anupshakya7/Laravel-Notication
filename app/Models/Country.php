<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $table = 'countries';

    public function country(){
        return $this->hasMany(Country::class,'parent_id');
    }

    public function state(){
        return $this->hasMany(SubCountry::class,'countrycode','country_code');
    }
}
