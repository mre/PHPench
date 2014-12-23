# PHPench

![A pretty graph](graph.gif)

PHPench creates a graphical output for a PHP benchmark.
Plot the runtime of any function in realtime with GnuPlot and create an image
out of the result.

## Example

```PHP
<?php

require_once __DIR__.'/../vendor/autoload.php';

/*
 * You can use an closure or a class that implements TestInterface.
 *
 * Data that will be processed by the tested function can be executed
 * without including its execution time. This will provide more accurate data.
 */

abstract class AbstractBenchmark implements \mre\PHPench\BenchmarkInterface
{
    protected $test;

    function setUp($arrSize)
    {
        $this->test = array();
        for ($i=1; $i<$arrSize; $i++) {
            $this->test[$i]= $arrSize % $i;
        }

        return $this->test;
    }
}

class TestArrayFlip extends AbstractBenchmark
{
    public function execute() {
        $test = array_flip(array_flip($this->test));
    }
}

class TestArrayUnique extends AbstractBenchmark
{
    public function execute() {
        $test = array_unique($this->test);
    }
}

// Create a new benchmark instance
$phpench = new mre\PHPench(new \mre\PHPench\Aggregator\MedianAggregator);

// Use GnuPlot for output
$oOutput = new \mre\PHPench\Output\GnuPlotOutput('test2.png', 1024, 768);

// Alternatively, print the values to the terminal
//$oOutput = new \mre\PHPench\Output\CliOutput();

$oOutput->setTitle('Compare array_flip and array_unique');
$phpench->setOutput($oOutput);

// Add your test to the instance
$phpench->addBenchmark(new TestArrayFlip, 'array_flip');
$phpench->addBenchmark(new TestArrayUnique, 'array_unique');

// Run the benchmark and plot the results in realtime.
// With the second parameter you can specify
// the start, end and step for each call
$phpench->setInput(range(1,pow(2,16), 1024));
$phpench->setRepetitions(4);
$phpench->run();
```

## Installation

1.) Add this package to your composer.json

```
{
    "require": {
      "mre/phpench": "*@dev"
    }
}
```

2.) Install gnuplot

For *Mac OS X* you can install gnuplot via homebrew. For live generated charts you also need to install XQuartz.
```
Without X11 support:
$ brew install gnuplot

With X11 supprt (recommended!):
$ brew install gnuplot --with-x
```

For *Linux* use your package manager.
```
apt-get install gnuplot
```

3.) Look at the examples for usage

## Maintainers

Matthias Endler (@matthiasendler)  
Markus Poerschke (@markuspoerschke)

## License

Apache License Version 2.0
