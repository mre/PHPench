<?php declare(strict_types=1);

namespace mre\PHPench\Output;

abstract class OutputAbstract implements OutputInterface
{
    /**
     * The title of the benchmark.
     *
     * @var string
     */
    protected $title = 'untitled';

    /**
     * Tests titles.
     *
     * @var array
     */
    protected $tests_titles = [];

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function addTest($tests_titles): void
    {
        $this->tests_titles[] = $tests_titles;
    }
}
