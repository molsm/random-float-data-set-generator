<?php

namespace MolsM\RandomFloatDataSetGenerator;

use MolsM\RandomFloatDataSetGenerator\Exceptions\DatumValueCAnNotBeDecreased;

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
     * @throws DatumValueCAnNotBeDecreased
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
     * @param int $priority
     * @return DatumInterface
     */
    public function setPriority(int $priority): DatumInterface;

    /**
     * @return int
     */
    public function getPriority(): int;

    /**
     * Skip datum if possible
     *
     * @return DatumInterface
     */
    public function skip(): DatumInterface;

    /**
     * @return bool
     */
    public function canBeSkippable(): bool;
}
