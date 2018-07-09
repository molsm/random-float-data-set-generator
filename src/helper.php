<?php

namespace MolsM\RandomFloatDataSetGenerator;

/**
 * @param array $array
 * @return array
 */
function shuffle_assoc(array $array)
{
    if (empty($array)) {
        return [];
    }

    $keys = array_keys($array);
    shuffle($keys);

    foreach ($keys as $key) {
        $new[$key] = $array[$key];
    }

    return $new;
}

/**
 * @param float $from
 * @param float $to
 * @param array $generatedByStep
 * @return float
 */
function get_random_float_number(float $from, float $to, array $generatedByStep): float
{
    $resultArray = array_map(function ($byStep) use ($from, $to) {
        return range($from, $to, $byStep);
    }, $generatedByStep);

    return \MolsM\RandomFloatDataSetGenerator\array_random(array_merge(...$resultArray));
}

/**
* Get one or a specified number of random values from an array.
 *
 * @param  array  $array
 * @param  int|null  $number
 * @return mixed
 *
 * @throws \InvalidArgumentException
 */
function array_random($array, $number = null)
{
    $requested = is_null($number) ? 1 : $number;

    $count = count($array);

    if ($requested > $count) {
        throw new \InvalidArgumentException(
            "You requested {$requested} items, but there are only {$count} items available."
        );
    }

    if (is_null($number)) {
        return $array[array_rand($array)];
    }

    if ((int) $number === 0) {
        return [];
    }

    $keys = array_rand($array, $number);

    $results = [];

    foreach ((array) $keys as $key) {
        $results[] = $array[$key];
    }

    return $results;
}
