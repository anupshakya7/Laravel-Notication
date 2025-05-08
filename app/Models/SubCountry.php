<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCountry extends Model
{
    use HasFactory;
    protected $table = 'sub_countries';

    public function country(){
        return $this->belongsTo(Country::class,'country_code','countrycode');
    }
}
