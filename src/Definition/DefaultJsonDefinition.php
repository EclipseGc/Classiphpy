<?php
/**
 * @file
 * Contains DefaultJsonDefinition.php.
 */

namespace Classiphpy\Definition;


class DefaultJsonDefinition implements DefinitionInterface {

  protected $namespace;

  protected $useStatements = [];

  protected $properties = [];

  function __construct($namespace, array $properties, array $useStatements = []) {
    $this->namespace = $namespace;
    $this->properties = $properties;
    $this->useStatements = $useStatements;
  }


  public function getNamespace() {
    return $this->namespace;
  }

  public function getUseStatements() {
    return $this->useStatements;
  }

  public function getProperties() {
    return $this->properties;
  }
} 