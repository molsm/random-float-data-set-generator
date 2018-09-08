<?php declare(strict_types=1);

namespace MolsM\RandomFloatDataSetGenerator;

use MolsM\RandomFloatDataSetGenerator\Exceptions\DatumValueCanNotBeDecreased;

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
     * @var int
     */
    private $priority = 0;

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
     * @throws DatumValueCanNotBeDecreased
     */
    public function decreaseValue(float $byAmount): DatumInterface
    {
        if (!$this->canBeDecreased($byAmount)) {
            throw new DatumValueCanNotBeDecreased('Datum can not be decreased');
        }

        $this->value -= $byAmount;

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
        if ($from > $to) {
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
     * Set priority for Datum in order to fill them accordingly
     *
     * @param int $priority
     * @return DatumInterface
     */
    public function setPriority(int $priority): DatumInterface
    {
        if ($priority < 0) {
            throw new \LogicException('Priority must be positive integer number');
        }

        $this->priority = $priority;

        return $this;
    }

    /**
     * @return int
     */
    public function getPriority(): int
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

    /**
     * @param float $byAmount
     * @return bool
     */
    private function canBeDecreased(float $byAmount): bool
    {
        return $this->value - $byAmount >= $this->from;
    }
}
