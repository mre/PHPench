<?php

require_once __DIR__.'/../vendor/autoload.php';

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
$phpench = new mre\PHPench();
$phpench->setTitle('Compare array_flip and array_unique');

// Add your test to the instance
$phpench->addTest($benchFunction1, 'array_flip');
$phpench->addTest($benchFunction2, 'array_unique');

// Run the benchmark and plot the results in realtime.
// With the second parameter you can specify
// the start, end and step for each call
$phpench->setInput(range(1,pow(2,16), 1024));
$phpench->run();
$phpench->save('test.png', 1024, 768);
