<?php

use PHPUnit\Framework\TestCase;
use MolsM\RandomFloatDataSetGenerator\Datum;

/**
 * @cover DataSet
 */
class DatumTest extends TestCase
{
    /**
     * @cover Datum::getValue()
     */
    public function testDefaultValueIsZeroFloat()
    {
        $this->assertEquals(0.0, (new Datum())->getValue());
    }

    /**
     * @expectedException \LogicException
     * @cover Datum::shouldBeBetween()
     */
    public function testBetweenSettleRanges()
    {
        (new Datum())->shouldBeBetween(3.0, 2.0);
    }

    /**
     * @expectedException \LogicException
     * @cover Datum::fillTillMaximum()
     */
    public function testIfDatumCanNotBeFilledTillMaximum()
    {
        (new Datum())->fillTillMaximum();
    }

    /**
     * @cover Datum::fillTillMaximum()
     */
    public function testIfDatumCanBeFilledTillMaximum()
    {
        $this->assertEquals(200.0, (new Datum())->shouldBeBetween(100.0, 200.0)->fillTillMaximum()->getValue());
    }
}