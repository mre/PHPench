<?php

namespace mre\PHPench;

require_once __DIR__.'/vendor/autoload.php';
use Gregwar\GnuPlot\GnuPlot;
use PHP_Timer;

class PHPench 
{
  public function __construct() {
    $this->plot = new GnuPlot;
    $this->plot->reset();
    $this->plot->setGraphTitle('PHPench benchmark');
    #$this->plot->setTitle(0, 'The moving sinus');
    #$this->plot->setTitle(1, 'The moving cosinus');
  }

  public function plot($benchFunction, $benchRange, $keepAlive=False) {
    foreach($benchRange as $i) {
      $this->bench($benchFunction, $i);
    }

    if ($keepAlive) {
      // Wait for user input to close
      echo "Press enter to quit.";
      fgets(STDIN);
    }
  }

  public function save($filename, $width=400, $height=300) {
    $this->plot->setWidth($width)
               ->setHeight($height)
               ->writePng($filename);
  }

  protected function bench($benchFunction, $i) {
    PHP_Timer::start();
    $benchFunction($i);
    $time = PHP_Timer::stop();
    $this->plot->push($i, $time);
    $this->plot->refresh();
  }
}
