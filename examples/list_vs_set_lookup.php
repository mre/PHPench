<?php declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

/*
 * Compare lookup in a list (O(n)) vs lookup in a set (O(1))
 */

use mre\PHPench\BenchmarkInterface;

abstract class AbstractBenchmark implements BenchmarkInterface
{
    protected $testList;
    protected $testSet;

    public function setUp($arrSize): void
    {
        // In PHP lists and sets are both implemented as arrays
        $this->testList = [];
        $this->testSet = [];

        for ($i = 1; $i < $arrSize; $i++) {
            $randKey = rand(0, 1000 * $arrSize);

            // Set element in the list
            $this->testList[] = $randKey;

            // Set element in the set
            $this->testSet[$randKey] = true;
        }
    }
}


class BenchmarkListLookup extends AbstractBenchmark
{
    public function execute()
    {
        $randKey = rand(1000, 10000);
        foreach ($this->testList as $id) {
            if ($randKey === $id) {
                // Key in array
                return true;
            }
        }
        // Key not in array
        return false;
    }
}

class BenchmarkSetLookup extends AbstractBenchmark
{
    public function execute()
    {
        $randKey = rand(1000, 10000);

        return isset($this->testSet[$randKey]);
    }
}

// Create a new benchmark instance
$phpench = new mre\PHPench(new \mre\PHPench\Aggregator\MedianAggregator());

// Use GnuPlot for output
$oOutput = new \mre\PHPench\Output\GnuPlotOutput('test2.png', 1024, 768);

// Alternatively, print the values to the terminal
//$oOutput = new \mre\PHPench\Output\CliOutput();
$oOutput->setTitle('Compare set and list lookup in PHP');
$phpench->setOutput($oOutput);

// Add your test to the instance
$phpench->addBenchmark(new BenchmarkSetLookup(), 'set');
$phpench->addBenchmark(new BenchmarkListLookup(), 'list');

// Run the benchmark and plot the results in realtime.
// With the second parameter you can specify
// the start, end and step for each call
$phpench->setInput(range(100, 1000, 10));
$phpench->setRepetitions(100);
$phpench->run();
