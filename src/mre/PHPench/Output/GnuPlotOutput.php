<?php

namespace mre\PHPench\Output;

use Gregwar\GnuPlot\GnuPlot;
use mre\PHPench\AggregatorInterface;

class GnuPlotOutput extends OutputAbstract
{
    /**
     * @var
     */
    private $filename;
    /**
     * @var int
     */
    private $width;
    /**
     * @var int
     */
    private $height;

    /**
     * @param $filename
     * @param int $width
     * @param int $height
     */
    public function __construct($filename, $width = 400, $height = 300)
    {
        $this->plot = new GnuPlot();

        $this->filename = $filename;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Updates plot data.
     *
     * @param AggregatorInterface $aggregator
     * @param $i
     *
     * @return mixed|void
     */
    public function update(AggregatorInterface $aggregator, $i)
    {
        $this->plot->reset();
        $this->plot->setGraphTitle($this->title);
        $this->plot->setXLabel('run');
        $this->plot->setYLabel('time');

        // set titles
        foreach ($this->tests_titles as $index => $title) {
            $this->plot->setTitle($index, $title);
        }

        foreach ($aggregator->getData() as $i => $results) {
            foreach ($results as $index => $resultValue) {
                $this->plot->push($i, $resultValue, $index);
            }
        }

        $this->plot->refresh();
    }

    /**
     * This method will save the graph as a PNG image.
     *
     * @param AggregatorInterface $aggregator
     * @param $i
     *
     * @return mixed|void
     */
    public function finalize(AggregatorInterface $aggregator, $i)
    {
        $this->update($aggregator, $i);

        $this->plot->setWidth($this->width)
            ->setHeight($this->height)
            ->writePng($this->filename);
    }
}
