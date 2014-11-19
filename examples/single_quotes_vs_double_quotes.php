<?php

require_once __DIR__.'/../vendor/autoload.php';

abstract class AbstractTest implements \mre\PHPench\TestInterface
{
    protected $test;

    function setUp($arrSize)
    {
    }
}

class TestSingleQuotes extends AbstractTest
{
    public function execute() {
      $test = 'hello' . 'this' . 'is' . 'a' . 'test';
    }
}

class TestDoubleQuotes extends AbstractTest
{
    public function execute() {
      $test = 'hello' . 'this' . 'is' . 'a' . 'test';
    }
}

// Create a new benchmark instance
$phpench = new mre\PHPench(new \mre\PHPench\Aggregator\MedianAggregator);
$phpench->setTitle('Compare single quote and double quote strings');

// Add your test to the instance
$phpench->addTest(new TestSingleQuotes, 'single_quotes');
$phpench->addTest(new TestDoubleQuotes, 'double_quotes');

// Run the benchmark and plot the results in realtime.
// With the second parameter you can specify
// the start, end and step for each call
$phpench->setInput(range(1,pow(2,16), 1024));
$phpench->setRepetitions(4);
$phpench->run(True);
