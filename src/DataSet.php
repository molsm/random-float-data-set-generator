<?php

namespace MolsM\RandomFloatDataSetGenerator;

class DataSet implements DataSetInterface
{
    /**
     * @var array
     */
    private $set = [];

    /**
     * @var float
     */
    private $amount;

    /**
     * DataSet constructor.
     * @param float $amount
     */
    public function __construct(float $amount)
    {
        $this->amount = $amount;
    }

    /**
     * @param DatumInterface $datum
     * @param $id mixed
     * @return DataSetInterface
     */
    public function addDatum(DatumInterface $datum, $id): DataSetInterface
    {
        $this->set[$id] = $datum;
        return $this;
    }

    /**
     * @throws \LogicException
     * @return float[]
     */
    public function generate(): array
    {
        if (empty($this->set)) {
            throw new \LogicException('Datum in set is empty. Expected at least one');
        }

        $this->processSet();

        $result = [];
        /** @var DatumInterface $datum */
        foreach ($this->set as $id => $datum) {
            $result[$id] = $datum->getValue();
        }

        return $result;
    }

    /**
     * @return void
     */
    private function processSet()
    {
        /** @var DatumInterface $datum */
        foreach ($this->set as $datum) {
            $datum->fillTillMaximum();
        }

        while ($this->amount !== ($sum = $this->getSum())) {
            if ($sum < $this->amount) {
                throw new \LogicException(
                    'Sum of datums became lower than settled amount. Generator does not know what to do next.'
                );
            }
        }
    }

    /**
     * @return float
     */
    private function getSum(): float
    {
        return array_reduce($this->set, function ($carry, $datum) {
            /** @var DatumInterface $datum */
            $carry += $datum->getValue();
            return $carry;
        });
    }

    /**
     * @param float $difference
     * @param bool $takeSmallestOne
     * @return array|mixed
     */
    private function getRandomSteps(float $difference, $takeSmallestOne = false)
    {
        // TODO: Implement logic
    }

    private function getRandomDatum(array $except): DatumInterface
    {
        // TODO: Implement logic
    }
}
