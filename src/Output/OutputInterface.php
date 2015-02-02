<?php
/**
 * @file
 * Contains OutputInterface.php.
 */
namespace Classiphpy\Output;

use Classiphpy\Definition\DefinitionInterface;
use Classiphpy\Input\InputInterface;

interface OutputInterface {

  /**
   * @param \Classiphpy\File\File[] $files
   * @return bool
   */
  public function writeOut(array $files);

  public function verify();

  public function getOutputDir();
}
