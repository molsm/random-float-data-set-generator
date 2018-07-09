<?php

namespace MolsM\RandomFloatDataSetGenerator;

class Datum implements DatumInterface
{
    /**
     * @var float
     */
    private $value = 0.0;

    /**
     * @var float|null
     */
    private $from;

    /**
     * @var float|null
     */
    private $to;

    /**
     * @var bool
     */
    private $priority = false;

    /**
     * @var bool
     */
    private $skip = false;

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param $byAmount
     * @return DatumInterface
     */
    public function decreaseValue(float $byAmount): DatumInterface
    {
        // TODO: Implement decreaseValue() method.
        return $this;
    }

    /**
     * @param float $from
     * @param float $to
     * @return Datum
     * @throws \LogicException
     */
    public function shouldBeBetween(float $from, float $to): DatumInterface
    {
        if ($from >= $to) {
            throw new \LogicException('Incorrect data ranges');
        }

        $this->from = $from;
        $this->to = $to;

        return $this;
    }

    /**
     * @return DatumInterface
     */
    public function fillTillMaximum(): DatumInterface
    {
        if ($this->from === null || $this->to === null) {
            throw new \LogicException('Ranges are not settled');
        }

        $this->value = $this->to;

        return $this;
    }

    /**
     * @return DatumInterface
     */
    public function priority(): DatumInterface
    {
        $this->priority = true;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPriority(): bool
    {
        return $this->priority;
    }

    /**
     * @return DatumInterface
     */
    public function skip(): DatumInterface
    {
        $this->skip = true;

        return $this;
    }

    /**
     * @return bool
     */
    public function canBeSkippable(): bool
    {
        return $this->skip;
    }
}
