<?php declare(strict_types=1);

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
     * @var GnuPlot
     */
    private $plot;

    /** @var int */
    private $precision = 14;

    /**
     * @param string $filename
     * @param int    $width
     * @param int    $height
     */
    public function __construct(string $filename, int $width = 400, int $height = 300)
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
     * @param int                 $i
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

        $data = $aggregator->getData();
        foreach ($data as $pos => $results) {
            foreach ($results as $index => $resultValue) {
                $this->plot->push($pos, number_format($resultValue, $this->precision), $index);
            }
        }

        $this->plot->plot();
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

    /**
     * @param int $precision
     *
     * @return GnuPlotOutput
     */
    public function setPrecision(int $precision): GnuPlotOutput
    {
        $this->precision = $precision;

        return $this;
    }
}
