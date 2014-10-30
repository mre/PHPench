<?php

namespace mre;

use Gregwar\GnuPlot\GnuPlot;
use PHP_Timer;

class PHPench
{
  private $plotIndex = -1;

  public function __construct($title = 'untitled') {
    $this->plot = new GnuPlot;
    $this->plot->reset();
    $this->plot->setGraphTitle($title);
    #$this->plot->setTitle(0, 'The moving sinus');
    #$this->plot->setTitle(1, 'The moving cosinus');
  }

  public function plot($benchFunction, $benchRange, $title, $keepAlive=False) {
    $this->plotIndex++;

    $this->plot->setTitle($this->plotIndex, $title);

    foreach($benchRange as $i) {
      $this->bench($benchFunction, $i, $this->plotIndex);
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

  protected function bench($benchFunction, $i, $plotIndex) {
    PHP_Timer::start();
    $benchFunction($i);
    $time = PHP_Timer::stop();
    $this->plot->push($i, $time, $plotIndex);
    $this->plot->refresh();
  }
}
