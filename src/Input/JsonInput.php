<?php
/**
 * @file
 * Contains JsonInput.php.
 */

namespace Classiphpy\Input;

class DefaultJsonInput extends InputBase {

  /**
   * {@inheritdoc}
   */
  public function parseInput($data) {
    $data = json_decode($data);
    parent::parseInput($data);
  }

  /**
   * {@inheritdoc}
   */
  public function validateInput(array &$data) {
    if (empty($data['classes'])) {
      return FALSE;
    }
    if (empty($data['defaults'])) {
      $data['defaults'] = [];
    }
    foreach ($data['classes'] as $class_id => $definition) {
      if (empty($definition['properties'])) {
        return FALSE;
      }
    }
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function definitionFactory(array $definition, array $defaults = []) {
    foreach (array_keys($defaults) as $key) {
      // If the definition has values for this key
      if (!empty($definition[$key])) {
        // and the definition and defaults are both arrays
        if (is_array($definition[$key]) && is_array($defaults[$key])) {
          // add them together.
          $definition[$key] += $defaults[$key];
        }
      }
      // If the definition has no values for this yet, trust the defaults.
      else {
        $definition[$key] = $defaults[$key];
      }
    }
    $reflector = new \ReflectionClass($this->definitionClass);
    if ($reflector->hasMethod('__construct')) {
      $arguments = [];
      foreach ($reflector->getMethod('__construct')->getParameters() as $param) {
        if (isset($definition[$param->getName()])) {
          $arguments[] = $definition[$param->getName()];
        }
      }
      return $reflector->newInstanceArgs($arguments);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function validationErrorMessage() {
    // @todo Consider storing errors during validation and returning them here.
    return 'The json array failed validation.';
  }


} 