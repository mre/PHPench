<?php

namespace mre\PHPench\Aggregator;

use mre\PHPench\AggregatorInterface;
use mre\PHPench\Util\Math;

/**
 * The average of the data will be calculated
 *
 * @author Markus Poerschke <markus@eluceo.de>
 */
class MedianAggregator implements AggregatorInterface
{
    private $data = [];

    public function push($i, $index, $value)
    {
        $this->data[$i][$index][] = $value;
    }

    public function getData()
    {
        $data = [];

        foreach ($this->data as $i => $iterationResults) {
            foreach ($iterationResults as $index => $testResults) {
                $data[$i][$index] = Math::median($testResults);
            }
        }

        return $data;
    }
}
