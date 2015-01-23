<?php
/**
 * @file
 * Contains Classiphpy.php.
 */

use Classiphpy\Input\InputInterface;
use Classiphpy\Output\OutputInterface;

class Classiphpy {

  /**
   * @var Classiphpy\Input\InputInterface
   */
  protected $input;

  /**
   * @var Classiphpy\Output\OutputInterface
   */
  protected $output;

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   */
  function __construct(InputInterface $input, OutputInterface $output) {
    $this->input = $input;
    $this->output = $output;
  }

  public function build($data) {
    $this->getInputMethod()->parseInput($data);
    return $this->getOutputMethod()->echoOut($this->getInputMethod());
  }

  public function getInputMethod() {
    return $this->input;
  }

  public function getOutputMethod() {
    return $this->output;
  }

}