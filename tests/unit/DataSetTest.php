<?php

use PHPUnit\Framework\TestCase;
use MolsM\RandomFloatDataSetGenerator\DataSet;

/**
 * @cover DataSet
 */
class DataSetTest extends TestCase
{
    /**
     * @expectedException \LogicException
     * @cover DataSet::generate()
     */
    public function testThrowsExceptionWhenDatumSetIsEmpty()
    {
        (new DataSet())->generate();
    }
}