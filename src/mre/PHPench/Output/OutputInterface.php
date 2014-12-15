<?php

namespace mre\PHPench\Output;


use mre\PHPench\AggregatorInterface;

interface OutputInterface {

    /**
     * Sets benchmark title
     *
     * @param $title
     * @return mixed
     */
    public function setTitle($title);

    /**
     * Adds single test title
     *
     * @param $tests_titles
     * @return mixed
     */
    public function addTest($tests_titles);

    /**
     * Executed after every test step
     *
     * @param AggregatorInterface $aggregator
     * @param $i
     * @return mixed
     */
    public function update(AggregatorInterface $aggregator, $i);

    /**
     * Executed after all tests
     *
     * @param AggregatorInterface $aggregator
     * @param $i
     * @return mixed
     */
    public function finalize(AggregatorInterface $aggregator, $i);
}