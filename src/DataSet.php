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
     * @return DataSetInterface
     */
    public function addDatum(DatumInterface $datum): DataSetInterface
    {
        // TODO: Implement addDatum() method.
        return $this;
    }

    /**
     * @throws \LogicException
     * @return float[]
     */
    public function generate(): array
    {
        if (empty($this->set)) {
            throw new \LogicException('Datum set is empty. Expected at least one');
        }

        $result = [];
        foreach ($this->set as $datum) {
            // TODO: Do someting
        }

        return $result;
    }
}