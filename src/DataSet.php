<?php

namespace MolsM\RandomFloatDataSetGenerator;

class DataSet implements DataSetInterface
{
    /**
     * @var array
     */
    private $set = [];

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

        $result = [];
        foreach ($this->set as $datum) {
            $datum->fill();
        }

        return $result;
    }
}