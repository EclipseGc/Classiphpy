<?php
/**
 * @file
 * Contains DefinitionInterface.php.
 */
namespace Classiphpy\Definition;

/**
 * Interface DefinitionInterface
 * @package Classiphpy\Definition
 *
 * The class definition interface.
 */
interface DefinitionInterface {

  /**
   * Reads the definition and defaults and returns a DefinitionInterface.
   *
   * @param array $definition
   *   The array of definition representing one unit of information from which
   *   to generate a DefinitionInterface.
   * @param array $defaults
   *   Defaults for all classes.
   * @return \Classiphpy\Definition\DefinitionInterface
   */
  public static function definitionFactory(array $definition, array $defaults = []);

  /**
   * Iterates over the data and returns an array of Definitions.
   *
   * @param array $data
   *   The array from which to generate class definitions.
   * @return \Classiphpy\Definition\DefinitionInterface[]
   */
  public static function iteratorFactory(array $data);

  /**
   * Validate that the data is usable for this Definition.
   *
   * @param array $data
   *   The array to validate.
   * @return bool
   */
  public static function validateData(array &$data);

  /**
   * The error to throw if the data does not validate.
   *
   * @return string
   *   Return a print-safe string.
   */
  public static function validationErrorMessage();

  /**
   * The class name.
   *
   * @return string
   */
  public function getName();

  /**
   * The namespace of the class.
   *
   * @return string
   */
  public function getNamespace();

  /**
   * The properties of this class.
   *
   * @return array
   */
  public function getProperties();

  /**
   * A list of dependencies this class requires.
   *
   * @return array
   */
  public function getDependencies();

  /**
   * Renders the class to a string.
   *
   * @return string
   */
  public function __toString();
}