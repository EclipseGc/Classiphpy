<?php
/**
 * @file
 * Contains ProcessorInterface.php.
 */
namespace Classiphpy\Processor;

interface ProcessorInterface {
  /**
   * @param \Classiphpy\Definition\DefinitionInterface[] $classes
   * @param array $data
   * @return \Classiphpy\File\File[]
   * @throws \Exception
   */
  public function process(array $classes, array $data);
}