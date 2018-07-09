<?php

namespace MolsM\RandomFloatDataSetGenerator;

interface DatumInterface
{
    /**
     * @return float
     */
    public function getValue(): float;

    /**
     * @return DatumInterface
     * @throws \LogicException
     */
    public function fillTillMaximum(): DatumInterface;

    /**
     * @param $byAmount
     * @return DatumInterface
     */
    public function decreaseValue(float $byAmount): DatumInterface;

    /**
     * @param float $from
     * @param $to
     * @return DatumInterface
     * @throws \LogicException
     */
    public function shouldBeBetween(float $from, float $to): DatumInterface;

    /**
     * Make datum filled in priority
     *
     * @return DatumInterface
     */
    public function priority(): DatumInterface;
}
