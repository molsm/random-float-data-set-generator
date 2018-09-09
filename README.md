# Random float data set generator
 
[![Build Status](https://travis-ci.org/molsm/random-float-data-set-generator.svg?branch=master)](https://travis-ci.org/molsm/random-float-data-set-generator)
[![Coverage Status](https://coveralls.io/repos/github/molsm/random-float-data-set-generator/badge.svg?branch=master)](https://coveralls.io/github/molsm/random-float-data-set-generator?branch=master)
[![Latest Stable Version](https://poser.pugx.org/molsm/random-float-data-set-generator/v/stable)](https://packagist.org/packages/molsm/random-float-data-set-generator)
[![License](https://poser.pugx.org/molsm/random-float-data-set-generator/license)](https://packagist.org/packages/molsm/random-float-data-set-generator)

Library that helps to generate random float datums in data set by defined rules.
Sum of datums values are equal to defined amount in data set.

# Getting started

## Prerequisites

1. PHP 7.0 or higher

## Installation

```
composer require molsm/random-float-data-set-generator
```

# Usage

1. Create `DataSet` object

```
$dataSet = new DataSet(5.0)
```

2. Add Datum to DataSet with your defined rules

```
$dataset->addDatum((new Datum())->shouldBeBetween(0.0, 10.0), 'id-example');
```

3. Call `generate` method

```
$result = $dataSet->generate();
```

4. Use resulting array

```
array('id-example' => 5.0)
```

# Test

Test are located under `tests/`

Run following command
```
composer test
```

# License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
