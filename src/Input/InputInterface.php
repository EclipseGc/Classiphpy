<?php
/**
 * @file
 * Contains InputInterface.php.
 */
namespace Classiphpy\Input;

interface InputInterface {
  /**
   * @param array $data
   * @return boolean
   */
  public function validateInput(array &$data);

  /**
   * @param array $definition
   * @param array $defaults
   * @return \Classiphpy\Definition\DefinitionInterface
   */
  public function definitionFactory(array $definition, array $defaults = []);

  /**
   * @return string
   *   Return a print-safe string.
   */
  public function validationErrorMessage();

  /**
   * @param $data
   * @throws \Exception
   * @return NULL
   */
  public function parseInput($data);

  /**
   * @return \Classiphpy\Definition\DefinitionInterface[]
   * @throws \Exception
   */
  public function getClasses();
}