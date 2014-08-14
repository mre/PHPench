<?php

include ('PHPench.php');

require_once __DIR__.'/vendor/autoload.php';
use mre\PHPench\PHPench;


/**
 * This is the function that we want to benchmark
 */
$benchFunction = function($arrSize) {
  // Setup
  $test=array();
  for($i=0; $i<$arrSize; $i++) {
    $test[]=rand(0,100);
  }
  array_flip($test);
};

// Create a new benchmark instance
$phpench = new PHPench();

// Run the benchmark and plot the results in realtime.
// With the second parameter you can specify
// the start, end and step for each call
$phpench->plot($benchFunction, range(1,pow(2,16), 1024));
$phpench->save('test.png', 1024, 768);
