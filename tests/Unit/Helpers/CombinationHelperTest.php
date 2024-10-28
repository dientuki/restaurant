<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Helpers\CombinationHelper;

class CombinationHelperTest extends TestCase
{
    public function testPeopleCount2()
    {
        $capacities = [2, 4, 6];
        $maxTables = 3;

        $result = CombinationHelper::getCombinations(2, $capacities, $maxTables);

        $expectedResult = [
            [2],
            [4],
            [6]
        ];

        $this->assertEquals($expectedResult, $result);
    }

    public function testPeopleCount3()
    {
        $capacities = [2, 4, 6];
        $maxTables = 3;

        $result = CombinationHelper::getCombinations(3, $capacities, $maxTables);

        $expectedResult = [
            [4],
            [2,2],
            [6]
        ];

        $this->assertEquals($expectedResult, $result);
    }

    public function testPeopleCount4()
    {
        $capacities = [2, 4, 6];
        $maxTables = 3;

        $result = CombinationHelper::getCombinations(4, $capacities, $maxTables);

        $expectedResult = [
            [4],
            [2, 2],
            [6]
        ];

        $this->assertEquals($expectedResult, $result);
    }

    public function testPeopleCount5()
    {
        $capacities = [2, 4, 6];
        $maxTables = 3;

        $result = CombinationHelper::getCombinations(5, $capacities, $maxTables);

        $expectedResult = [
            [6],
            [2, 4],
            [2, 2, 2],
            [4, 4]
        ];

        $this->assertEquals($expectedResult, $result);
    }

    public function testPeopleCount6()
    {
        $capacities = [2, 4, 6];
        $maxTables = 3;

        $result = CombinationHelper::getCombinations(6, $capacities, $maxTables);

        $expectedResult = [
            [6],
            [2, 4],
            [2, 2, 2],
            [4, 4]
        ];

        $this->assertEquals($expectedResult, $result);
    }

    public function testPeopleCount7()
    {
        $capacities = [2, 4, 6];
        $maxTables = 3;

        $result = CombinationHelper::getCombinations(7, $capacities, $maxTables);

        $expectedResult = [
            [6,2], //l
            [4,4], //l
            [4,2,2],
            [6,4], //l
            [6,6] //l
        ];

        //dd($result, $expectedResult);

        $this->assertEquals($expectedResult, $result);
    }

    public function testPeopleCount8()
    {
        $capacities = [2, 4, 6];
        $maxTables = 3;

        $result = CombinationHelper::getCombinations(8, $capacities, $maxTables);

        $expectedResult = [
            [6,2], //l
            [4,4], //l
            [4,2,2],
            [6,4], //l
            [6,6] //l
        ];

        //dd($result, $expectedResult);

        $this->assertEquals($expectedResult, $result);
    }

    public function testPeopleCount9()
    {
        $capacities = [2, 4, 6];
        $maxTables = 3;

        $result = CombinationHelper::getCombinations(9, $capacities, $maxTables);

        $expectedResult = [
            [6,4],
            [6,2,2],
            [6,6],
            [4,4,4]
        ];



        $this->assertEquals($expectedResult, $result);
    }

    public function testPeopleCount10()
    {
        $capacities = [2, 4, 6];
        $maxTables = 3;

        $result = CombinationHelper::getCombinations(10, $capacities, $maxTables);

        $expectedResult = [
            [6,4],
            [6,2,2],
            [6,6],
            [4,4,4]
        ];



        $this->assertEquals($expectedResult, $result);
    }

    public function testPeopleCount11()
    {
        $capacities = [2, 4, 6];
        $maxTables = 3;

        $result = CombinationHelper::getCombinations(11, $capacities, $maxTables);

        $expectedResult = [
            [6,6],
            [4,4,4],
            [6,4,4]
        ];



        $this->assertEquals($expectedResult, $result);
    }

    public function testPeopleCount12()
    {
        $capacities = [2, 4, 6];
        $maxTables = 3;

        $result = CombinationHelper::getCombinations(12, $capacities, $maxTables);

        $expectedResult = [
            [6,6],
            [4,4,4],
            [6,4,4]
        ];

        $this->assertEquals($expectedResult, $result);
    }

    public function testPeopleCount13()
    {
        $capacities = [2, 4, 6];
        $maxTables = 3;

        $result = CombinationHelper::getCombinations(13, $capacities, $maxTables);

        $expectedResult = [
            [6,4,4],
            [6,6,2],
            [6,6,4],
            [6,6,6]
        ];

        //dd($result, $expectedResult);

        $this->assertEquals($expectedResult, $result);
    }

    public function testPeopleCount14()
    {
        $capacities = [2, 4, 6];
        $maxTables = 3;

        $result = CombinationHelper::getCombinations(14, $capacities, $maxTables);

        $expectedResult = [
            [6,4,4],
            [6,6,2],
            [6,6,4], //l
            [6,6,6] //l
        ];

        //dd($result, $expectedResult);

        $this->assertEquals($expectedResult, $result);
    }

    public function testPeopleCount15()
    {
        $capacities = [2, 4, 6];
        $maxTables = 3;

        $result = CombinationHelper::getCombinations(15, $capacities, $maxTables);

        $expectedResult = [
            [6,6,4],
            [6,6,6],
        ];

        $this->assertEquals($expectedResult, $result);
    }

    public function testPeopleCount16()
    {
        $capacities = [2, 4, 6];
        $maxTables = 3;

        $result = CombinationHelper::getCombinations(16, $capacities, $maxTables);

        $expectedResult = [
            [6,6,4],
            [6,6,6],
        ];

        $this->assertEquals($expectedResult, $result);
    }

    public function testPeopleCount17()
    {
        $capacities = [2, 4, 6];
        $maxTables = 3;

        $result = CombinationHelper::getCombinations(17, $capacities, $maxTables);

        $expectedResult = [
            [6,6,6],
        ];

        //dd($result, $expectedResult);

        $this->assertEquals($expectedResult, $result);
    }

    public function testPeopleCount18()
    {
        $capacities = [2, 4, 6];
        $maxTables = 3;

        $result = CombinationHelper::getCombinations(18, $capacities, $maxTables);

        $expectedResult = [
            [6,6,6],
        ];

        //dd($result, $expectedResult);

        $this->assertEquals($expectedResult, $result);
    }
}
