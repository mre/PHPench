<?php

require_once __DIR__.'/../vendor/autoload.php';

/*
 * You can use an closure or a class that implements TestInterface.
 *
 * Data that will be processed by the tested function can be executed
 * without including its execution time. This will provide more accurate data.
 */

abstract class AbstractTest implements \mre\PHPench\TestInterface
{
    protected $test;

    function setUp($arrSize)
    {
        $this->test = array();
        for ($i=0; $i<$arrSize; $i++) {
            $this->test[]= mt_rand(0, $arrSize*10);
        }
        return $this->test;
    }
}

class TestBubbleSort extends AbstractTest
{
    private function bubblesort($arr = array ()) {
        $anz = count($arr);
        $temp='';
        for ($a = 0; $a < $anz; $a++) {
            for ($b = 0; $b < $anz -1; $b++) {
                if ($arr[$b +1] < $arr[$b]) {
                    $temp = $arr[$b];
                    $arr[$b] = $arr[$b +1];
                    $arr[$b +1] = $temp;
                }
            }
        }
        return $arr;
    } 

    public function execute() {
        $sorted = $this->bubblesort($this->test);
    }
}

class TestQuickSort extends AbstractTest
{
    private function quicksort($seq) {
        if(!count($seq)) return $seq;
        $pivot= $seq[0];
        $low = $high = array();
        $length = count($seq);
        for($i=1; $i < $length; $i++) {
            if($seq[$i] <= $pivot) {
                $low [] = $seq[$i];
            } else {
                $high[] = $seq[$i];
            }
        }
        return array_merge($this->quicksort($low), array($pivot), $this->quicksort($high));
    }

    public function execute() {
        $sorted = $this->quicksort($this->test);
    }
}

// Create a new benchmark instance
$phpench = new mre\PHPench(new \mre\PHPench\Aggregator\MedianAggregator);
$phpench->setTitle('Sorting Algorithms');

// Add your test to the instance
$phpench->addTest(new TestBubbleSort, 'bubblesort');
$phpench->addTest(new TestQuickSort, 'quicksort');

// Run the benchmark and plot the results in realtime.
// With the second parameter you can specify
// the start, end and step for each call
$phpench->setInput(range(1 ,pow(2,16), 128));
$phpench->run();
$phpench->save('sorting_algorithms.png', 1024, 768);
