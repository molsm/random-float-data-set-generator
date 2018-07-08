<?php

namespace MolsM\RandomFloatDataSetGenerator\Tests;

use MolsM\RandomFloatDataSetGenerator\Datum;
use PHPUnit\Framework\TestCase;
use MolsM\RandomFloatDataSetGenerator\DataSet;

/**
 * @cover DataSet
 */
class DataSetTest extends TestCase
{
    /**
     * @expectedException \LogicException
     * @covers DataSet::generate()
     */
    public function testThrowsExceptionWhenDatumSetIsEmpty()
    {
        (new DataSet(0.0))->generate();
    }

    /**
     * @dataProvider correctGeneration
     * @covers       DataSet::generate()
     * @param $amount
     * @param array $data
     */
    public function testGenerate($amount, array $data)
    {
        $dataSet = new DataSet($amount);

        foreach ($data as $key => $betweenValues) {
            $dataSet->addDatum(
                (new Datum())->shouldBeBetween($betweenValues[0], $betweenValues[1]),
                $key
            );
        }

        $this->assertEquals($amount, array_reduce($dataSet->generate(), function ($carry, $datum) {
            $carry += $datum;
            return $carry;
        }));
    }

    /**
     * @return array
     */
    public function correctGeneration(): array
    {
        return [
            [100.0, [[10.0, 50.0], [5.0, 50.0]]]
        ];
    }
}
