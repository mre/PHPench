<?php

namespace mre\PHPench;

/**
 * This is a wrapper for the function to be benchmarked.
 *
 * This Interface can be used instead of a \Closure
 * if you need to create data before executing your benchmark function.
 *
 * @author Markus Poerschke <markus@eluceo.de>
 */
interface BenchmarkInterface
{
    /**
     * Prepares the data
     *
     * @param $i
     */
    public function setUp($i);

    /**
     * Calls the tested function
     */
    public function execute();
}
