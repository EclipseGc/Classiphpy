<?php
/**
 * @file
 * Contains Classiphpy.php.
 */


use Classiphpy\Output\OutputInterface;
use Classiphpy\Processor\ProcessorInterface;

/**
 * Class Classiphpy
 *
 * Composes the various Classiphpy components together for ease of use.
 */
class Classiphpy {

  /**
   * An array of DefinitionInterface implementing classes.
   *
   * @var array
   */
  protected $definitionClasses;

  /**
   * @var Classiphpy\Output\OutputInterface
   */
  protected $output;

  /**
   * Create new instance of Classiphpy
   *
   * @param array $definition_classes
   *   An array of class names which implement \Classiphpy\Definition\DefintionInterface
   * @param OutputInterface $output
   *   The OutputInterface class that will render our File(s).
   * @throws Exception
   */
  function __construct(array $definition_classes, OutputInterface $output) {
    foreach ($definition_classes as $definition_class) {
      if (!is_subclass_of($definition_class, '\Classiphpy\Definition\DefinitionInterface')) {
        /* @todo Throw a custom exception */
        throw new \Exception(sprintf('The %s class is not an instance of \Classiphpy\Definition\DefinitionInterface', $definitionClass));
      }
    }
    $this->definitionClasses = $definition_classes;
    $this->output = $output;
  }

  /**
   * Defines, processes and writes the File(s) derived from $data.
   *
   * @param array $data
   *   The data from which to generate new DefinitionInterface object(s).
   * @param ProcessorInterface $processor
   *   The processor to turn DefinitionInterface object(s) into File(s).
   */
  public function build(array $data, ProcessorInterface $processor) {
    $classes = [];
    foreach ($this->getDefinitionClasses() as $definitionClass) {
      /** @var $definitionClass \Classiphpy\Definition\DefinitionInterface */
      $classes += $definitionClass::iteratorFactory($data);
    }
    $this->getOutputMethod()->writeOut($processor->process($classes, $data));
    /* @todo determine how to define success/failure and return a boolean, or some message stack */
  }

  /**
   * Retrieve the array of definition classes.
   *
   * @return array
   */
  public function getDefinitionClasses() {
    return $this->definitionClasses;
  }

  /**
   * Retrieve the OutputInterface object.
   *
   * @return OutputInterface
   */
  public function getOutputMethod() {
    return $this->output;
  }

}
