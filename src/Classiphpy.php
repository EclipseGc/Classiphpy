<?php
/**
 * @file
 * Contains Classiphpy.php.
 */


use Classiphpy\Output\OutputInterface;
use Classiphpy\Processor\ProcessorInterface;

class Classiphpy {

  /**
   * @var array
   *   An array of DefinitionInterface implementing classes.
   */
  protected $definitionClasses;

  /**
   * @var Classiphpy\Output\OutputInterface
   */
  protected $output;

  /**
   * @param array $definition_classes
   * @param OutputInterface $output
   * @throws Exception
   */
  function __construct(array $definition_classes, OutputInterface $output) {
    foreach ($definition_classes as $definition_class) {
      if (!is_subclass_of($definition_class, '\Classiphpy\Definition\DefinitionInterface')) {
        throw new \Exception(sprintf('The %s class is not an instance of \Classiphpy\Definition\DefinitionInterface', $definitionClass));
      }
    }
    $this->definitionClasses = $definition_classes;
    $this->output = $output;
  }

  public function build($data, ProcessorInterface $processor) {
    $classes = [];
    foreach ($this->getDefinitionClasses() as $definitionClass) {
      /** @var $definitionClass \Classiphpy\Definition\DefinitionInterface */
      $test = $definitionClass::iteratorFactory($data);
      $classes += $test;
    }
    return $this->getOutputMethod()->writeOut($processor->process($classes, $data));
  }

  public function getDefinitionClasses() {
    return $this->definitionClasses;
  }

  public function getOutputMethod() {
    return $this->output;
  }

}
