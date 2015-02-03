<?php
/**
 * @file
 * Contains ProcessorInterface.php.
 */
namespace Classiphpy\Processor;

/**
 * Interface ProcessorInterface
 * @package Classiphpy\Processor
 *
 * Processors turn an array of DefinitionInterface objects and the original
 * data array into an array of File objects.
 */
interface ProcessorInterface {
  /**
   * Process the classes and data creating an array of File objects for output.
   *
   * @param \Classiphpy\Definition\DefinitionInterface[] $classes
   *   An array of class definitions to process.
   * @param array $data
   *   The original data array from which the class definitions were created.
   * @return \Classiphpy\File\File[]
   *   An array of files to create based upon the classes and data.
   */
  public function process(array $classes, array $data);
}