<?php

namespace MolsM\RandomFloatDataSetGenerator\Tests;

use MolsM\RandomFloatDataSetGenerator\DatumInterface;
use PHPUnit\Framework\TestCase;
use MolsM\RandomFloatDataSetGenerator\Datum;

/**
 * @cover DataSet
 */
class DatumTest extends TestCase
{
    /**
     * @covers Datum::getValue()
     */
    public function testDefaultValueIsZeroFloat()
    {
        $this->assertEquals(0.0, (new Datum())->getValue());
    }

    /**
     * @expectedException \LogicException
     * @covers Datum::shouldBeBetween()
     */
    public function testBetweenSettleRanges()
    {
        (new Datum())->shouldBeBetween(3.0, 2.0);
    }

    /**
     * @expectedException \LogicException
     * @covers Datum::fillTillMaximum()
     */
    public function testIfDatumCanNotBeFilledTillMaximum()
    {
        (new Datum())->fillTillMaximum();
    }

    /**
     * @covers Datum::fillTillMaximum()
     */
    public function testIfDatumCanBeFilledTillMaximum()
    {
        $this->assertEquals(200.0, (new Datum())->shouldBeBetween(100.0, 200.0)->fillTillMaximum()->getValue());
    }

    /**
     * @expectedException \LogicException
     * @cover Datum::setPriority()
     */
    public function testSetNegativePriorityNumber()
    {
        (new Datum())->setPriority(-1);
    }

    /**
     * @cover Datum::setPriority()
     */
    public function testSetPositivePriorityNumber()
    {
        /** @noinspection UnnecessaryAssertionInspection */
        $this->assertInstanceOf(DatumInterface::class, (new Datum())->setPriority(1));
    }
}
