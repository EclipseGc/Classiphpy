<?php
/**
 * @file
 * Contains OutputInterface.php.
 */
namespace Classiphpy\Output;

use Classiphpy\Definition\DefinitionInterface;
use Classiphpy\Input\InputInterface;

interface OutputInterface {

  public function writeOut(InputInterface $input);

  public function verify();

  public function getOutputDir();

  public function getTemplateOutput($class_id, DefinitionInterface $definition);
}