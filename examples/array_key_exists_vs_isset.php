<?php declare(strict_types=1);

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

    public function setUp($arrSize)
    {
        $this->test = [];
        for ($i = 0; $i < $arrSize; ++$i) {
            //$str = uniqid();
            $this->test['key_' . $i] = 'value_' . $i;
        }

        return $this->test;
    }
}

class BenchmarkIsset extends AbstractBenchmark
{
    public function execute(): void
    {
        $bla = isset($this->test['doesnotexist']);
    }
}

class BenchmarkArrayKeyExists extends AbstractBenchmark
{
    public function execute(): void
    {
        $bla = array_key_exists('doesnotexist', $this->test);
    }
}

// Create a new benchmark instance
$phpench = new mre\PHPench(new \mre\PHPench\Aggregator\MedianAggregator());

// Use GnuPlot for output
$oOutput = new \mre\PHPench\Output\GnuPlotOutput('test2.png', 1024, 768);

// Alternatively, print the values to the terminal
//$oOutput = new \mre\PHPench\Output\CliOutput();

$oOutput->setTitle('Compare isset and array_key_exists');
$phpench->setOutput($oOutput);

// Add your test to the instance
$phpench->addBenchmark(new BenchmarkIsset(), 'isset');
$phpench->addBenchmark(new BenchmarkArrayKeyExists(), 'array_key_exists');

// Run the benchmark and plot the results in realtime.
// With the second parameter you can specify
// the start, end and step for each call
$phpench->setInput(range(1, pow(2, 16), 1024));
$phpench->setRepetitions(4);
$phpench->run();
