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
     * @expectedException \LogicException
     * @covers DataSet::generate()
     */
    public function testThrowsExceptionWhenDatumMinRangeIsMoreThanDefinedAmount()
    {
        (new DataSet(150.0))
            ->addDatum((new Datum())->shouldBeBetween(100.0, 250.0), 1)
            ->addDatum((new Datum())->shouldBeBetween(100.0, 250.0), 2)
            ->generate();
    }

    /**
     * @dataProvider correctGenerationWithoutPriority
     * @covers DataSet::generate()
     * @param $amount
     * @param array $data
     */
    public function testGenerateWithoutPriority($amount, array $data)
    {
        $dataSet = new DataSet($amount);

        foreach ($data as $key => $betweenValues) {
            $dataSet->addDatum(
                (new Datum())->shouldBeBetween($betweenValues[0], $betweenValues[1]),
                $key
            );
        }

        $generatedDataSet = $dataSet->generate();
        $resultSum = array_reduce($generatedDataSet, function ($carry, $datum) {
            $carry += $datum;
            return $carry;
        });

        $this->assertEquals($amount, $resultSum);
    }

    /**
     * [[expected, [[from, to], [from, to] + 1....]]]
     *
     * @return array
     */
    public function correctGenerationWithoutPriority(): array
    {
        return [
            [150.0, [[100.0, 250.0], [0.0, 250.0]]],
            [69.0, [[10.0, 50.0], [5.0, 50.0], [5.0, 50.0]]],
            [65.0, [[10.0, 50.0], [5.0, 50.0], [45.0, 50.0]]],
            [65.0, [[10.0, 50.0], [10.0, 50.0], [45.0, 50.0]]],
            [145.0, [[10.0, 50.0], [5.0, 50.0], [45.0, 50.0]]],
            [150.0, [[10.0, 50.0], [5.0, 50.0], [45.0, 50.0]]],
            [14.0, [[10.0, 50.0], [0.0, 50.0], [0.0, 50.0]]],
            [100.0, [[10.0, 50.0], [0.0, 50.0], [45.0, 50.0]]],
            [175.0, [[0.0, 50.0], [0.0, 50.0], [0.0, 100.0]]],
            [10000.0, [[100.0, 500.0], [500.0, 5000.0], [500.0, 4900.0]]],
        ];
    }

    /**
     * @dataProvider correctGenerationWithPriority
     * @covers DataSet::generate()
     * @param $amount
     * @param array $data
     */
    public function testGenerateWithPriority($amount, array $data, array $datumMustBeSettled)
    {
        $dataSet = new DataSet($amount);

        foreach ($data as $id => $values) {
            $dataSet->addDatum(
                (new Datum())->shouldBeBetween($values['from'], $values['to'])->setPriority($values['priority']),
                $id
            );
        }

        $generatedDaset = $dataSet->generate();
        $resultSum = array_reduce($generatedDaset, function ($carry, $datum) {
            $carry += $datum;
            return $carry;
        });

        foreach ($datumMustBeSettled as $id) {
            $this->assertTrue($generatedDaset[$id] > 0.0, 'Not settled with priority');
        }

        $this->assertEquals($amount, $resultSum);
    }

    /**
     * [[expected, [[from, to, priority], [from, to, priority] + 1....]], [datumMustBeSettled]]
     *
     * @return array
     */
    public function correctGenerationWithPriority(): array
    {
        return [
            [
                65.0,
                [
                    '1' => ['from' => 10.0, 'to' => 50.0, 'priority' => 3],
                    '2' => ['from' => 5.0, 'to' => 50.0, 'priority' => 100],
                    '3' => ['from' => 35.0, 'to' => 50.0, 'priority' => 1]
                ],
                []
            ],
            [
                50.0,
                [
                    '1' => ['from' => 10.0, 'to' => 50.0, 'priority' => 3],
                    '2' => ['from' => 5.0, 'to' => 50.0, 'priority' => 100],
                    '3' => ['from' => 35.0, 'to' => 50.0, 'priority' => 1]
                ],
                []
            ],
            [
                70.0,
                [
                    '1' => ['from' => 0.0, 'to' => 50.0, 'priority' => 3],
                    '2' => ['from' => 0.0, 'to' => 50.0, 'priority' => 100],
                    '3' => ['from' => 35.0, 'to' => 50.0, 'priority' => 1],
                    '4' => ['from' => 0.0, 'to' => 50.0, 'priority' => 1],
                    '5' => ['from' => 0.0, 'to' => 50.0, 'priority' => 2],
                ],
                [3, 4]
            ],
            [
                599.0,
                [
                    '1' => ['from' => 0.0, 'to' => 600.0, 'priority' => 3],
                    '2' => ['from' => 0.0, 'to' => 500.0, 'priority' => 100],
                    '3' => ['from' => 0.0, 'to' => 50.0, 'priority' => 1],
                    '4' => ['from' => 0.0, 'to' => 50.0, 'priority' => 1],
                    '5' => ['from' => 0.0, 'to' => 50.0, 'priority' => 0],
                ],
                [3, 4, 1]
            ],
            [
                601.0,
                [
                    '1' => ['from' => 0.0, 'to' => 600.0, 'priority' => 3],
                    '2' => ['from' => 0.0, 'to' => 500.0, 'priority' => 0],
                    '3' => ['from' => 0.0, 'to' => 50.0, 'priority' => 2],
                    '4' => ['from' => 0.0, 'to' => 600.0, 'priority' => 1],
                    '5' => ['from' => 0.0, 'to' => 50.0, 'priority' => 0],
                ],
                [4, 3]
            ]
        ];
    }
}
