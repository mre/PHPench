<?php

namespace mre\PHPench;

/**
 * This is a wrapper for the tested function.
 *
 * This Interface can be used instead of an \Closure
 * if you need to create data before executing your test function.
 *
 * @author Markus Poerschke <markus@eluceo.de>
 */
interface TestInterface
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
