<?php declare(strict_types=1);

namespace mre\PHPench\Aggregator;

use mre\PHPench\AggregatorInterface;

/**
 * Simple aggregator that will simply replace the data.
 *
 * @author Markus Poerschke <markus@eluceo.de>
 */
class SimpleAggregator implements AggregatorInterface
{
    private $data = [];

    public function push($i, $index, $value): void
    {
        $this->data[$i][$index] = $value;
    }

    public function getData()
    {
        return $this->data;
    }
}
