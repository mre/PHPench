<?php

namespace mre\PHPench;

/**
 * Aggregates the data from the test results.
 *
 * @author Markus Poerschke <markus@eluceo.de>
 */
interface AggregatorInterface
{
    /**
     * Adds a new value to the aggregator.
     *
     * @param $i
     * @param $index
     * @param $value
     */
    public function push($i, $index, $value);

    /**
     * Returns the aggregated data as an array.
     *
     * array(
     *    run_1 => array (TEST_1 => 123, TEST_2 => 456, ...)
     *    ...
     * )
     *
     * @return array
     */
    public function getData();
}
