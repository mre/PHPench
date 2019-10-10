<?php declare(strict_types=1);

namespace mre;

use mre\PHPench\Aggregator\SimpleAggregator;
use mre\PHPench\AggregatorInterface;
use mre\PHPench\Output\OutputInterface;
use mre\PHPench\BenchmarkInterface;
use SebastianBergmann\Timer\Timer;

/**
 * PHPench.
 *
 * This class provides the core functionality for the PHPench package.
 *
 * @link http://github.com/mre/PHPench
 *
 * @author Matthias Endler <matthias-endler@gmx.net>
 * @author Markus Poerschke <markus@eluceo.de>
 */
class PHPench
{
    private $tests = [];

    /**
     * @var OutputInterface
     */
    private $output = null;

    /**
     * Contains an array with the run numbers.
     *
     * @var array
     */
    private $input = [];

    /**
     * @var AggregatorInterface
     */
    private $aggregator;

    /**
     * The number of times the bench should be executed.
     *
     * This can increase the precise.
     *
     * @var int
     */
    private $repetitions = 3;

    public function __construct(AggregatorInterface $aggregator = null)
    {
        if (null === $aggregator) {
            $aggregator = new SimpleAggregator();
        }

        $this->aggregator = $aggregator;
    }

    /**
     * sets output interface.
     *
     * @param OutputInterface $output
     */
    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }

    /**
     * Add a function to the benchmark.
     *
     * @param callable|BenchmarkInterface $test
     * @param string                      $title
     */
    public function addBenchmark($test, $title): void
    {
        if (!$test instanceof \Closure && !$test instanceof BenchmarkInterface) {
            throw new \InvalidArgumentException('Test must be closure or implement TestInterface');
        }

        $this->tests[] = $test;
        $this->output->addTest($title);
    }

    /**
     * Plots the graph for all added tests.
     *
     * @param bool $keepAlive
     */
    public function run($keepAlive = false): void
    {
        for ($r = 1; $r <= $this->repetitions; ++$r) {
            foreach ($this->input as $i) {
                foreach ($this->tests as $index => $test) {
                    $this->bench($test, $i, $index);
                }

                $this->output->update($this->aggregator, $i);
            }
        }

        $this->output->finalize($this->aggregator, $i);

        if ($keepAlive) {
            // Wait for user input to close
            echo 'Press enter to quit.';
            fgets(STDIN);
        }
    }

    /**
     * @param array $input
     */
    public function setInput(array $input): void
    {
        $this->input = $input;
    }

    /**
     * @param $repetitions
     */
    public function setRepetitions($repetitions): void
    {
        $this->repetitions = $repetitions;
    }

    private function bench($benchFunction, $i, $index): void
    {
        if ($benchFunction instanceof BenchmarkInterface) {
            $benchFunction->setUp($i);
            Timer::start();
            $benchFunction->execute();
            $time = Timer::stop();
        } else {
            Timer::start();
            $benchFunction($i);
            $time = Timer::stop();
        }

        $this->aggregator->push($i, $index, $time);
    }
}
