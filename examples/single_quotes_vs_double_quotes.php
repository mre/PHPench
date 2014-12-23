<?php

require_once __DIR__.'/../vendor/autoload.php';

abstract class AbstractBenchmark implements \mre\PHPench\BenchmarkInterface
{
    protected $test;

    function setUp($arrSize)
    {
    }
}

class TestSingleQuotes extends AbstractBenchmark
{
    public function execute() {
      $test = 'hello' . 'this' . 'is' . 'a' . 'test';
    }
}

class TestDoubleQuotes extends AbstractBenchmark
{
    public function execute() {
      $test = "hello" . "this" . "is" . "a" . "test";
    }
}

// Create a new benchmark instance
$phpench = new mre\PHPench(new \mre\PHPench\Aggregator\MedianAggregator);

$output = new \mre\PHPench\Output\GnuPlotOutput('test4.png',  1024, 768);
$output->setTitle('Compare single quote and double quote strings');
$phpench->setOutput($output);


// Add your test to the instance
$phpench->addBenchmark(new TestSingleQuotes, 'single_quotes');
$phpench->addBenchmark(new TestDoubleQuotes, 'double_quotes');

// Run the benchmark and plot the results in realtime.
// With the second parameter you can specify
// the start, end and step for each call
$phpench->setInput(range(1,pow(2,16), 1024));
$phpench->setRepetitions(4);
$phpench->run(True);
