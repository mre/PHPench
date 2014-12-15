<?php

namespace mre\PHPench\Output;


abstract class OutputAbstract implements OutputInterface{

    /**
     * The title of the benchmark
     *
     * @var string
     */
    protected $title = 'untitled';

    /**
     * Tests titles
     *
     * @var array
     */
    protected $tests_titles = [];

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function addTest($tests_titles)
    {
        $this->tests_titles[] = $tests_titles;
    }

} 