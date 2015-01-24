<?php
/**
 * @file
 * Contains DefinitionInterface.php.
 */
namespace Classiphpy\Definition;

interface DefinitionInterface {
  public function getProperties();

  public function getUseStatements();

  public function getNamespace();
}