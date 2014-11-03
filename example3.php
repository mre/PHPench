<?php

require_once __DIR__.'/vendor/autoload.php';

/*
 * You can use an closure or a class that implements TestInterface.
 *
 * Data that will be processed by the tested function can be executed
 * without including its execution time. This will provide more accurate data.
 */

abstract class AbstractTest implements \mre\PHPench\TestInterface
{
    protected $text;
    protected $placeholders;

    public function setUp($arrSize)
    {
        $this->text = $this->createText(10);
        $this->placeholders = $this->createPlaceholders($arrSize);
    }

    protected function createText($n)
    {
        $text = 'Lorem Ipsum Text ';

        for ($i = 0; $i < $n; $i++) {
            $text .= ' placeholder: $placeholder_' . $i;
        }

        return $text;
    }

    protected function createPlaceholders($n)
    {
        $placeholders = [];

        for ($i = 0; $i < $n; $i++) {
            $placeholders['$placeholder_' . $i] = $i;
        }

        return $placeholders;
    }
}

class TestStringReplaceForeach extends AbstractTest
{
    public function execute() {
        foreach ($this->placeholders as $search => $replace) {
            $this->text = str_replace($search, $replace, $this->text);
        }
    }
}

class TestStringReplaceArrayValue extends AbstractTest
{
    public function execute() {
        $this->text = str_replace(array_keys($this->placeholders), array_values($this->placeholders), $this->text);
    }
}

class TestPregReplaceCallback extends AbstractTest
{
    public function execute() {
        $this->text = preg_replace_callback('/(\$[\w\d]+)\s/', function($matches) {
            return isset($this->placeholder[$matches[0]]) ? $this->placeholder[$matches[0]] : $matches[0];
        }, $this->text);
    }
}

// Create a new benchmark instance
$phpench = new mre\PHPench(new \mre\PHPench\Aggregator\MedianAggregator);
$phpench->setTitle('Compare placeholder replacement');

// Add your test to the instance
$phpench->addTest(new TestStringReplaceForeach, 'TestStringReplaceForeach');
$phpench->addTest(new TestStringReplaceArrayValue, 'TestStringReplaceArrayValue');
$phpench->addTest(new TestPregReplaceCallback, 'TestPregReplaceCallback');

// Run the benchmark and plot the results in realtime.
// With the second parameter you can specify
// the start, end and step for each call
$phpench->setInput(range(0, 200, 2));
$phpench->setRepetitions(10);
$phpench->run();
$phpench->save('test3.png', 1024, 768);
