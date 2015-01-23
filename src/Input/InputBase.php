<?php
/**
 * @file
 * Contains InputBase.php.
 */

namespace Classiphpy\Input;

abstract class InputBase implements InputInterface {

  protected $definitionClass;

  /**
   * @var \Classiphpy\Definition\DefinitionInterface[]
   */
  protected $classes = [];

  /**
   * @param $definitionClass
   * @throws \Exception
   */
  function __construct($definitionClass) {
    if (!is_subclass_of($definitionClass, '\Classiphpy\Definition\DefinitionInterface')) {
      throw new \Exception(sprintf('The %s class is not an instance of \Classiphpy\Definition\DefinitionInterface', $definitionClass));
    }
    $this->definitionClass = $definitionClass;
  }

  /**
   * {@inheritdoc}
   */
  public function parseInput($data) {
    if (!$this->validateInput($data)) {
      throw new \Exception($this->validationErrorMessage());
    }
    foreach ($data['classes'] as $class_id => $definition) {
      if (!is_numeric($class_id)) {
        $this->classes[$class_id] = $this->definitionFactory($definition, $data['defaults']);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getClasses() {
    if (empty($this->classes)) {
      throw new \Exception('No classes found, try running the parseInput() method on this class before calling getClasses().');
    }
    return $this->classes;
  }

  /**
   * {@inheritdoc}
   */
  abstract public function validateInput(array &$data);

  /**
   * {@inheritdoc}
   */
  abstract public function definitionFactory(array $definition, array $defaults = []);

  /**
   * @return string
   *   Return a print-safe string.
   */
  abstract public function validationErrorMessage();

} 