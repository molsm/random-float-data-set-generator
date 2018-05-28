<?php

namespace MolsM\RandomFloatDataSetGenerator;

interface DataSetInterface
{
    /**
     * Return array key => float
     *
     * @return float[]
     */
    public function generate(): array;
}