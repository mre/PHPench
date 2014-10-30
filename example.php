<?php

require_once __DIR__.'/vendor/autoload.php';

// setup our test data
function createArray($arrSize)
{
    $test = array();
    for ($i=1; $i<$arrSize; $i++) {
        $test[$i]= $arrSize % $i;
    }

  return $test;
}

/**
 * These are the functions that we want to benchmark
 *
 * This test compares array_flip vs array_unique
 */
$benchFunction1 = function ($arrSize) {
    $test = createArray($arrSize);
    $test = array_flip(array_flip($test));
};
$benchFunction2 = function ($arrSize) {
    $test = createArray($arrSize);
    $test = array_unique($test);
};

// Create a new benchmark instance
$phpench = new mre\PHPench('Compare array_flip and array_unique');

// Run the benchmark and plot the results in realtime.
// With the second parameter you can specify
// the start, end and step for each call
$phpench->plot($benchFunction1, range(1,pow(2,16), 1024), 'array_flip');
$phpench->plot($benchFunction2, range(1,pow(2,16), 1024), 'array_unique');
$phpench->save('test.png', 1024, 768);
