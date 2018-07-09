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
     * @var array
     */
    private $preDefinedRandomStepsAmountMap = [];

    /**
     * DataSet constructor.
     * @param float $amount
     * @param array|null $preDefinedRandomStepsAmountMap
     */
    public function __construct(float $amount, array $preDefinedRandomStepsAmountMap = null)
    {
        $this->amount = $amount;
        $this->preDefinedRandomStepsAmountMap = $preDefinedRandomStepsAmountMap;
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
     * @return array|mixed
     */
    private function getRandomSteps(float $difference)
    {
        $result = [];

        foreach ($this->preDefinedRandomStepsAmountMap as $amount => $values) {
            if ((float) $amount <= $difference) {
                $result = \MolsM\RandomFloatDataSetGenerator\shuffle_assoc($values);
                break;
            }
        }

        return $result;
    }

    /**
     * @param array $except
     * @return DatumInterface
     */
    private function getRandomAvailableDatumFromSet(array $except): DatumInterface
    {
        // TODO: Implement logic
    }
}
