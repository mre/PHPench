<?php

namespace mre;

use Gregwar\GnuPlot\GnuPlot;
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
     * Contains an array with the run numbers
     *
     * @var array
     */
    private $input = [];

    public function __construct($title = 'untitled')
    {
        $this->plot = new GnuPlot();
        $this->plot->reset();
        $this->plot->setGraphTitle($title);
        $this->plot->setXLabel('run');
        $this->plot->setYLabel('time');
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
        // set titles
        foreach ($this->titles as $index => $title) {
            $this->plot->setTitle($index, $title);
        }

        foreach ($this->input as $i) {
            foreach ($this->tests as $index => $test) {
                $this->bench($test, $i, $index);
            }

            $this->plot->refresh();
        }

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

    private function bench($benchFunction, $i, $plotIndex)
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

        $this->plot->push($i, $time, $plotIndex);
    }
}
