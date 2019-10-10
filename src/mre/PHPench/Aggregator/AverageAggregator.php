<?php declare(strict_types=1);

namespace mre\PHPench\Aggregator;

use mre\PHPench\AggregatorInterface;

/**
 * The average of the data will be calculated.
 *
 * @author Markus Poerschke <markus@eluceo.de>
 */
class AverageAggregator implements AggregatorInterface
{
    private $data = [];

    public function push($i, $index, $value): void
    {
        if (!isset($this->data[$i][$index])) {
            $this->data[$i][$index] = $value;

            return;
        }

        $this->data[$i][$index] += $value;
        $this->data[$i][$index] /= 2;
    }

    public function getData()
    {
        return $this->data;
    }
}
