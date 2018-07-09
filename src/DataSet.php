<?php

namespace MolsM\RandomFloatDataSetGenerator;

use function \MolsM\RandomFloatDataSetGenerator\shuffle_assoc;

class DataSet implements DataSetInterface
{
    const PRE_DEFINED_RANDOM_STEPS_AMOUNT_MAP = [
        '500.0' => [100.0, 50.0],
        '100.0' => [25.0, 10.0],
        '10.0' => [5.0],
        '5.0' => [5.0],
        '1.0' => [1.0],
    ];

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
    private $preDefinedRandomStepsAmountMap;

    /**
     * DataSet constructor.
     * @param float $amount
     * @param array|null $preDefinedRandomStepsAmountMap
     */
    public function __construct(
        float $amount,
        array $preDefinedRandomStepsAmountMap = self::PRE_DEFINED_RANDOM_STEPS_AMOUNT_MAP
    ) {
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

            $difference = $sum - $this->amount;
            $excludedDatums = [];

            foreach ($this->getRandomSteps($difference) as $randomStep) {
                try {
                    $datum = $this->getRandomAvailableDatumFromSet($excludedDatums);
                } catch (\Exception $exception) {
                }
            }
        }
    }

    /**
     * @return float
     */
    private function getSum(): float
    {
        return array_reduce($this->set, function ($sum, $datum) {
            /** @var DatumInterface $datum */
            $sum += $datum->getValue();
            return $sum;
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
                $result = shuffle_assoc($values);
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
