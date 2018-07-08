<?php

namespace MolsM\RandomFloatDataSetGenerator\Tests;

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
        (new DataSet(0.0))->generate();
    }
}
