<?php

namespace mre;

use Gregwar\GnuPlot\GnuPlot;
use mre\PHPench\Aggregator\SimpleAggregator;
use mre\PHPench\AggregatorInterface;
use mre\PHPench\TestInterface;
use PHP_Timer;

/**
 * PHPench
 *
 * This class provides the core functionality for the PHPench package.
 *
 * @link http://github.com/mre/PHPench
 *
 * @author Matthias Endler <matthias-endler@gmx.net>
 * @author Markus Poerschke <markus@eluceo.de>
 */
class PHPench
{
    private $tests = [];
    private $titles = [];

    /**
     * The title of the benchmark
     *
     * @var string
     */
    private $title = 'untitled';

    /**
     * Contains an array with the run numbers
     *
     * @var array
     */
    private $input = [];

    /**
     * @var AggregatorInterface
     */
    private $aggregator;

    /**
     * The number of times the bench should be executed.
     *
     * This can increase the precise.
     *
     * @var int
     */
    private $repetitions = 3;

    public function __construct(AggregatorInterface $aggregator = null)
    {
        if ($aggregator === null) {
            $aggregator = new SimpleAggregator();
        }

        $this->plot = new GnuPlot();
        $this->aggregator = $aggregator;
    }

    /**
     * Adds an test to the bench instance
     *
     * @param callable $test
     */
    public function addTest($test, $title)
    {
        if (!$test instanceof \Closure && !$test instanceof TestInterface) {
            throw new \InvalidArgumentException('Test must be closure or implement TestInterface');
        }

        $this->tests[] = $test;
        $this->titles[] = $title;
    }

    /**
     * Plots the graph for all added tests
     *
     * @param bool $keepAlive
     */
    public function run($keepAlive = false)
    {
        for ($r = 1; $r <= $this->repetitions; $r++)
        {
            foreach ($this->input as $i) {
                foreach ($this->tests as $index => $test) {
                    $this->bench($test, $i, $index);
                }

                $this->plot();
            }
        }

        // make sure that the graph will be plotted at the very end...
        $this->plot();

        if ($keepAlive) {
            // Wait for user input to close
            echo "Press enter to quit.";
            fgets(STDIN);
        }
    }

    /**
     * This method will save the graph as a PNG image
     *
     * @param string $filename
     * @param int    $width
     * @param int    $height
     */
    public function save($filename, $width = 400, $height = 300)
    {
        $this->plot->setWidth($width)
                   ->setHeight($height)
                   ->writePng($filename);
    }

    /**
     * @param array $input
     */
    public function setInput(array $input)
    {
        $this->input = $input;
    }

    /**
     * @param $repetitions
     */
    public function setRepetitions($repetitions)
    {
        $this->repetitions = $repetitions;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    private function plot()
    {
        $this->plot->reset();
        $this->plot->setGraphTitle($this->title);
        $this->plot->setXLabel('run');
        $this->plot->setYLabel('time');

        // set titles
        foreach ($this->titles as $index => $title) {
            $this->plot->setTitle($index, $title);
        }

        foreach ($this->aggregator->getData() as $i => $results) {
            foreach ($results as $index => $resultValue) {
                $this->plot->push($i, $resultValue, $index);
            }
        }

        $this->plot->refresh();
    }

    private function bench($benchFunction, $i, $index)
    {
        if ($benchFunction instanceof TestInterface) {
            $benchFunction->setUp($i);
            PHP_Timer::start();
            $benchFunction->execute();
            $time = PHP_Timer::stop();
        } else {
            PHP_Timer::start();
            $benchFunction($i);
            $time = PHP_Timer::stop();
        }

        $this->aggregator->push($i, $index, $time);
    }
}
