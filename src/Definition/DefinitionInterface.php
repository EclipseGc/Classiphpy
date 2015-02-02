<?php
/**
 * @file
 * Contains DefinitionInterface.php.
 */
namespace Classiphpy\Definition;

interface DefinitionInterface {

  /**
   * @param array $definition
   * @param array $defaults
   * @return \Classiphpy\Definition\DefinitionInterface
   */
  public static function definitionFactory(array $definition, array $defaults = []);

  /**
   * @param array $data
   * @return $this[]
   */
  public static function iteratorFactory(array $data);

  /**
   * @param array $data
   * @return bool
   */
  public static function validateData(array &$data);

  /**
   * @return string
   *   Return a print-safe string.
   */
  public static function validationErrorMessage();

  public function getName();

  public function getNamespace();

  public function getProperties();

  public function getDependencies();

  public function __toString();
}