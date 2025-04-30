<?php

namespace Tests\Unit;

use App\Helpers\ConvertUsdToEuro;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    public function test_convert_usd_to_eur_successful(){
        $result = (new ConvertUsdToEuro())->convert(100,'usd','eur');
        $this->assertEquals(98,$result);
    }

    public function test_convert_usd_to_gbp_returns_zero(){
        $result = (new ConvertUsdToEuro())->convert(100,'usd','gbp');
        $this->assertEquals(0,$result);
    }
}
