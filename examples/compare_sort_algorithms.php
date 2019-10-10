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
        for ($i = 0; $i < $arrSize; $i++) {
            $this->test[] = mt_rand(0, $arrSize * 10);
        }

        return $this->test;
    }
}

class BenchmarkBubbleSort extends AbstractBenchmark
{
    private function bubblesort($arr = [])
    {
        $anz = count($arr);
        $temp = '';
        for ($a = 0; $a < $anz; $a++) {
            for ($b = 0; $b < $anz - 1; $b++) {
                if ($arr[$b + 1] < $arr[$b]) {
                    $temp = $arr[$b];
                    $arr[$b] = $arr[$b + 1];
                    $arr[$b + 1] = $temp;
                }
            }
        }

        return $arr;
    }

    public function execute(): void
    {
        $sorted = $this->bubblesort($this->test);
    }
}

class BenchmarkQuickSort extends AbstractBenchmark
{
    private function quicksort($seq)
    {
        if (!count($seq)) {
            return $seq;
        }
        $pivot = $seq[0];
        $low = $high = [];
        $length = count($seq);
        for ($i = 1; $i < $length; $i++) {
            if ($seq[$i] <= $pivot) {
                $low [] = $seq[$i];
            } else {
                $high[] = $seq[$i];
            }
        }

        return array_merge($this->quicksort($low), [$pivot], $this->quicksort($high));
    }

    public function execute(): void
    {
        $sorted = $this->quicksort($this->test);
    }
}

// Create a new benchmark instance
$phpench = new mre\PHPench(new \mre\PHPench\Aggregator\MedianAggregator());
$output = new \mre\PHPench\Output\GnuPlotOutput('sorting_algorithms.png', 1024, 768);
$output->setTitle('Sorting Algorithms');
$phpench->setOutput($output);

// Add your test to the instance
$phpench->addBenchmark(new BenchmarkBubbleSort(), 'bubblesort');
$phpench->addBenchmark(new BenchmarkQuickSort(), 'quicksort');

// Run the benchmark and plot the results in realtime.
// With the second parameter you can specify
// the start, end and step for each call
$phpench->setInput(range(1, pow(2, 16), 128));
$phpench->run();
