<?php declare(strict_types=1);

namespace MolsM\RandomFloatDataSetGenerator;

use MolsM\RandomFloatDataSetGenerator\Exceptions\DatumValueCanNotBeDecreased;
use MolsM\RandomFloatDataSetGenerator\Exceptions\NotSuitableRandomStepHasBeenChoosen;
use function \MolsM\RandomFloatDataSetGenerator\shuffle_assoc;

class DataSet implements DataSetInterface
{
    /**
     * @var DatumInterface[]
     */
    private $set = [];

    /**
     * @var float
     */
    private $amount;

    /**
     * DataSet constructor.
     * @param float $amount
     * @param array|null $preDefinedRandomStepsAmountMap
     */
    public function __construct(
        float $amount
    ) {
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

        while ($this->amount !== ($sum = $this->getSum())) {
            if ($sum < $this->amount) {
                throw new \LogicException(
                    'Sum of datums became lower than settled amount. Generator does not know what to do next.'
                );
            }

            $difference = $sum - $this->amount;
            $datumValueWasDecreased = false;

            foreach ($this->getAvailableDatumsFromSet() as $datum) {
                foreach ($this->getRandomSteps($difference) as $randomByAmount) {
                    try {
                        if ($sum - $randomByAmount < $this->amount) {
                            throw new NotSuitableRandomStepHasBeenChoosen(
                                'Can not be decreased. Result sum would be lower'
                            );
                        }
                        $datum->decreaseValue($randomByAmount);
                        $datumValueWasDecreased = true;
                        break;
                    } catch (NotSuitableRandomStepHasBeenChoosen $exception) {
                        continue;
                    } catch (DatumValueCanNotBeDecreased $exception) {
                        continue;
                    }
                }

                if ($datumValueWasDecreased) {
                    break;
                }
            }

            if (!$datumValueWasDecreased) {
                throw new \LogicException('Processed all datums and values was not decreased');
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
     * @param float $difference
     * @return array
     */
    private function getRandomSteps(float $difference): array
    {
        $multiplier = $difference >= 100 ? floor($difference / 100): 1;
        $randomSteps = shuffle_assoc([5.0 * $multiplier, 10.0 * $multiplier]);

        return array_merge($randomSteps, [5.0, 1.0]);
    }

    /**
     * @return array
     */
    private function getAvailableDatumsFromSet(): array
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

            $datums = $this->set;
            uasort($datums, $sorting);
        } else {
            $datums = shuffle_assoc($this->set);
        }

        return $datums;
    }
}
