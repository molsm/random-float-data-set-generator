<?php

namespace MolsM\RandomFloatDataSetGenerator;

interface DataSetInterface
{
    /**
     * @param DatumInterface $datum
     * @return mixed
     */
    public function addDatum(DatumInterface $datum): DataSetInterface;

    /**
     * Return array key => float
     *
     * @throws \LogicException
     * @return float[]
     */
    public function generate(): array;
}