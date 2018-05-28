<?php

namespace MolsM\RandomFloatDataSetGenerator;

class Datum implements DatumInterface
{
    /**
     * @return float
     */
    public function getValue(): float
    {
        // TODO: Implement getValue() method.
        return 0.0;
    }

    /**
     * @param $byAmount
     * @return DatumInterface
     */
    public function increaseValue($byAmount): DatumInterface
    {
        // TODO: Implement increaseValue() method.
        return $this;
    }

    /**
     * @param $byAmount
     * @return DatumInterface
     */
    public function decreaseValue($byAmount): DatumInterface
    {
        // TODO: Implement decreaseValue() method.
        return $this;
    }
}