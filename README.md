# PHPench

PHPench creates a graphical output for a PHP benchmark.
Plot the runtime of any function in realtime with GnuPlot and create an image
out of the result.

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

![A pretty graph](https://github.com/mre/PHPench/blob/master/test.png)
