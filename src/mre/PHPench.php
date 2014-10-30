<?php

namespace mre;

use Gregwar\GnuPlot\GnuPlot;
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
    private $plotIndex = -1;

    public function __construct($title = 'untitled')
    {
        $this->plot = new GnuPlot();
        $this->plot->reset();
        $this->plot->setGraphTitle($title);
    }

    /**
     * Plots the graph for the given function.
     *
     * @param \Closure $benchFunction
     * @param array    $benchRange
     * @param string   $title
     * @param bool     $keepAlive
     */
    public function plot(\Closure $benchFunction, array $benchRange, $title, $keepAlive = false)
    {
        $this->plotIndex++;

        $this->plot->setTitle($this->plotIndex, $title);

        foreach ($benchRange as $i) {
            $this->bench($benchFunction, $i, $this->plotIndex);
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

    private function bench($benchFunction, $i, $plotIndex)
    {
        PHP_Timer::start();
        $benchFunction($i);
        $time = PHP_Timer::stop();
        $this->plot->push($i, $time, $plotIndex);
        $this->plot->refresh();
    }
}
