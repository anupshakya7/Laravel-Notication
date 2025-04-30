<?php

namespace App\Models;

use App\Helpers\ConvertUsdToEuro;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getPriceEurAttribute(){
        return (new ConvertUsdToEuro())->convert($this->price,'usd','eur');
    }
}
