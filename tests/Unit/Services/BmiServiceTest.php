<?php

namespace Tests\Unit\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Services\BmiService;
use App\Models\Person;

class BmiServiceTest extends TestCase
{
    /**
     * BMI caluc logic test
     *
     * @dataProvider bmiDataProvider
     * @param $height
     * @param $weight
     * @param $result
     * @return void
     */
    public function testCalcBmi($height, $weight, $result)
    {
        $bmiService = new BmiService();
        // ReflectionMethodで、privateメソッドのアクセス権を上書きする
        $method = new \ReflectionMethod(get_class($bmiService), 'calcBmi');
        $method->setAccessible(true);
        // invokeメソッドで、実際にprivateメソッドを呼び出している
        $actual = $method->invoke($bmiService, $height, $weight);
        $this->assertEquals($result, $actual,'', 0.2);
    }

    /**
     * BMI get logic test
     *
     * @dataProvider bmiDataProvider
     * @param $height
     * @param $weight
     * @param $result
     * @return void
     */
    public function testGetBmi($height, $weight, $result)
    {
        $person = new Person();
        $person->height = $height;
        $person->weight = $weight;
        $actual = BmiService::getBmi($person);
        $this->assertEquals($result, $actual,'', 0.2);
    }

    public function bmiDataProvider()
    {
        return [
            [1.6,60,23.4735],
            [1.8,80,24.6914],
            [1,0,false],
            [0,50,false],
        ];
    }
}
