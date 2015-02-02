<?php
/**
 * @file
 * Contains DefaultJsonDefinition.php.
 */

namespace Classiphpy\Definition;


use Pharborist\FormatterFactory;
use Pharborist\Functions\ParameterNode;
use Pharborist\Node;
use Pharborist\Objects\ClassMethodNode;
use Pharborist\Objects\ClassNode;
use Pharborist\Parser;
use Pharborist\RootNode;
use Pharborist\WhitespaceNode;

class DefaultJsonDefinition implements DefinitionInterface {

  protected $name;

  protected $namespace;

  protected $properties = [];

  function __construct($name, $namespace, array $properties) {
    $this->name = $name;
    $this->namespace = $namespace;
    $this->properties = $properties;
  }

  public function getName() {
    return $this->name;
  }

  public function getNamespace() {
    return $this->namespace;
  }

  public function getProperties() {
    return $this->properties;
  }

  public function getDependencies() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public static function definitionFactory(array $definition, array $defaults = []) {
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
    $reflector = new \ReflectionClass(get_called_class());
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
  public static function iteratorFactory(array $data) {
    if (!static::validateData($data)) {
      throw new \Exception(static::validationErrorMessage());
    }
    $classes = [];
    foreach ($data['classes'] as $class_id => $definition) {
      if (!is_numeric($class_id)) {
        $definition['name'] = $class_id;
        $classes[$class_id] = static::definitionFactory($definition, $data['defaults']);
      }
    }
    return $classes;
  }

  /**
   * {@inheritdoc}
   */
  public static function validateData(array &$data) {
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
  public static function validationErrorMessage() {
    // @todo Consider storing errors during validation and returning them here.
    return 'The json array failed validation.';
  }

  protected function toString() {
    $doc = RootNode::create($this->getNamespace());
    $class = ClassNode::create($this->getName());
    $constructor = ClassMethodNode::create('__construct');
    $class->appendMethod($constructor);

    foreach ($this->getProperties() as $name) {
      //$default = Node::fromValue($default_value);
      $class->createProperty($name, NULL, 'protected');
      $constructor->appendParameter(ParameterNode::create($name));
      $expression = Parser::parseSnippet("\$this->{$name} = \$$name;");
      $constructor->getBody()->lastChild()->before($expression);
    }
    if ($this->getProperties()) {
      $constructor->getBody()->lastChild()->before(WhitespaceNode::create("\n"));
    }

    $doc->getNamespace($this->getNamespace())->append($class);
    $formatter = FormatterFactory::getPsr2Formatter();
    $formatter->format($doc->getNamespace($this->getNamespace()));
    return $doc->getText();
  }

  public function __toString() {
    try {
      return $this->toString();
    }
    catch (\Exception $e) {
      print_r($e);
      return "\n";
    }
  }
}