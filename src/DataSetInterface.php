<?php

namespace MolsM\RandomFloatDataSetGenerator;

interface DataSetInterface
{
    /**
     * @param DatumInterface $datum
     * @param $id mixed
     * @return mixed
     */
    public function addDatum(DatumInterface $datum, $id): DataSetInterface;

    /**
     * Return array key => float
     *
     * @throws \LogicException
     * @return float[]
     */
    public function generate(): array;
}