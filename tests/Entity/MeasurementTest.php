<?php

namespace App\Tests\Entity;

use App\Entity\Measurement;
use PHPUnit\Framework\TestCase;

class MeasurementTest extends TestCase
{
 
    public function dataGetFahrenheit(): array
    {
        return [
            ['0', 32],
            ['-100', -148],
            ['100', 212],
            ['0.5', 32.9],
            ['-50.5', -59.9],
            ['37', 98.6],
            ['25', 77],
            ['-273.15', -459.67],
            ['15.5', 59.9],
            ['20.3', 68.54],
        ];
    }

    /**
     * @dataProvider dataGetFahrenheit
     */
    public function testGetFahrenheit($celsius, $expectedFahrenheit): void
    {
        $measurement = new Measurement();
        $measurement->setCelsius($celsius);
        $this->assertEquals($expectedFahrenheit, $measurement->getFahrenheit());
    }
}
