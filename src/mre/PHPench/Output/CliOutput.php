<?php

namespace mre\PHPench\Output;

use mre\PHPench\AggregatorInterface;

class CliOutput extends OutputAbstract
{
    /**
     * Displays execution information
     *
     * @param AggregatorInterface $aggregator
     * @return void
     */
    public function update(AggregatorInterface $aggregator, $i)
    {
        printf("Executed repetitions: %s".PHP_EOL, $i);

        foreach ($this->tests_titles as $index => $title) {
            printf('Test "%s" execution time: %f '.PHP_EOL, $title, @end(@$aggregator->getData())[$index]);
        }
    }

    /**
     *
     * @param AggregatorInterface $aggregator
     */
    public function finalize(AggregatorInterface $aggregator, $i)
    {
        printf("Done");
    }

} 