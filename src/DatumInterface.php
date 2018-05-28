<?php

namespace MolsM\RandomFloatDataSetGenerator;

interface DatumInterface
{
    /**
     * @return float
     */
    public function getValue(): float;

    /**
     * @param $byAmount
     * @return DatumInterface
     */
    public function increaseValue($byAmount): DatumInterface;

    /**
     * @param $byAmount
     * @return DatumInterface
     */
    public function decreaseValue($byAmount): DatumInterface;
}