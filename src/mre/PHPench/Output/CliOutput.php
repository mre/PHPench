<?php declare(strict_types=1);

namespace mre\PHPench\Output;

use mre\PHPench\AggregatorInterface;

class CliOutput extends OutputAbstract
{
    /**
     * Displays execution information.
     *
     * @param AggregatorInterface $aggregator
     * @param mixed               $i
     */
    public function update(AggregatorInterface $aggregator, $i): void
    {
        printf('Executed repetitions: %s'.PHP_EOL, $i);

        foreach ($this->tests_titles as $index => $title) {
            printf('Test "%s" execution time: %f '.PHP_EOL, $title, @end(@$aggregator->getData())[$index]);
        }
    }

    /**
     * @param AggregatorInterface $aggregator
     * @param mixed               $i
     */
    public function finalize(AggregatorInterface $aggregator, $i): void
    {
        printf('Done');
    }
}
