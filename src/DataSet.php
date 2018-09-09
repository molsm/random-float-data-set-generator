<?php declare(strict_types=1);

namespace MolsM\RandomFloatDataSetGenerator;

use MolsM\RandomFloatDataSetGenerator\Exceptions\DatumValueCanNotBeDecreased;
use MolsM\RandomFloatDataSetGenerator\Exceptions\NotSuitableRandomStepHasBeenChoosen;
use function \MolsM\RandomFloatDataSetGenerator\shuffle_assoc;

class DataSet implements DataSetInterface
{
    const PRE_DEFINED_RANDOM_STEPS_AMOUNT_MAP = [
        '100.0' => [15.0, 10.0],
        '10.0' => [5.0],
        '5.0' => [5.0],
        '1.0' => [1.0],
    ];

    /**
     * @var DatumInterface[]
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
        if ($this->amount < array_reduce($this->set, function ($sum, $datum) {
            /** @var DatumInterface $datum */
            $sum += $datum->getFrom();
            return $sum;
        })) {
            throw new \LogicException('Amount is lower than sum of datums minimum ranges');
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

        $excludedDatumsIds = [];
        $jumpToLowest = 0;

        while ($this->amount !== ($sum = $this->getSum())) {
            if ($sum < $this->amount) {
                throw new \LogicException(
                    'Sum of datums became lower than settled amount. Generator does not know what to do next.'
                );
            }

            $difference = $sum - $this->amount;
            $datumValueWasDecreased = false;
            $datumId = $this->getAvailableDatumIdFromSet($excludedDatumsIds);

            foreach ($this->getRandomSteps($difference, $jumpToLowest) as $randomByAmount) {
                try {
                    if ($sum - $randomByAmount < $this->amount) {
                        throw new NotSuitableRandomStepHasBeenChoosen(
                            'Can not be decreased. Result sum would be lower'
                        );
                    }
                    $this->set[$datumId]->decreaseValue($randomByAmount);
                    $datumValueWasDecreased = true;
                } catch (NotSuitableRandomStepHasBeenChoosen $exception) {
                    $jumpToLowest++;
                    break;
                } catch (DatumValueCanNotBeDecreased $exception) {
                    continue;
                }
            }

            if (!$datumValueWasDecreased) {
                $excludedDatumsIds[] = $datumId;

                if (count($excludedDatumsIds) === count($this->set)) {
                    $excludedDatumsIds = [];
                }
            }
        }
    }

    /**
     * @return float
     */
    private function getSum(): float
    {
        return (float) array_reduce($this->set, function ($sum, $datum) {
            /** @var DatumInterface $datum */
            $sum += $datum->getValue();
            return $sum;
        });
    }

    /**
     * Shuffle - key of random here
     *
     * @param float $difference
     * @param int $jumpToLowest
     * @return array|mixed
     */
    private function getRandomSteps(float $difference, $jumpToLowest = 0)
    {
        $result = [];
        $jumpCounter = 0;

        foreach ($this->preDefinedRandomStepsAmountMap as $amount => $values) {
            if ((float) $amount <= $difference) {
                if ($jumpCounter < $jumpToLowest) {
                    $jumpCounter++;
                    continue;
                }
                $result = shuffle_assoc($values);
                break;
            }
        }

        if (empty($result)) {
            throw new \LogicException('Can not pick any step');
        }

        return $result;
    }

    /**
     * @param array $except
     * @return string
     */
    private function getAvailableDatumIdFromSet(array $except): string
    {
        $hasPrioritySettled = false;
        foreach ($this->set as $id => $datum) {
            if ($datum->getPriority() !== 0) {
                $hasPrioritySettled = true;
                break;
            }
        }

        if ($hasPrioritySettled) {
            $sorting = function ($a, $b) {
                /** @var $a DatumInterface */
                /** @var $b DatumInterface */
                if ($a->getPriority() === $b->getPriority()) {
                    return 0;
                }

                if ($b->getPriority() === 0) {
                    return 1;
                }

                return ($a->getPriority() < $b->getPriority()) ? 1 : -1;
            };

            $tempSet = $this->set;
            uasort($tempSet, $sorting);
        } else {
            $tempSet = shuffle_assoc($this->set);
        }

        foreach ($tempSet as $id => $datum) {
            if (!\in_array($id, $except, false)) {
                return (string) $id;
            }
        }

        throw new \LogicException('Can not retrieve any datum');
    }
}
