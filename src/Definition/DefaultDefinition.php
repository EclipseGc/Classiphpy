<?php
/**
 * @file
 * Contains DefaultJsonDefinition.php.
 */

namespace Classiphpy\Definition;


use Pharborist\DocCommentNode;
use Pharborist\DocCommentTest;
use Pharborist\Filter;
use Pharborist\FormatterFactory;
use Pharborist\Functions\ParameterNode;
use Pharborist\Objects\ClassMethodNode;
use Pharborist\Objects\ClassNode;
use Pharborist\Parser;
use Pharborist\RootNode;

/**
 * Class DefaultDefinition
 * @package Classiphpy\Definition
 *
 * The default class definition class.
 */
class DefaultDefinition implements DefinitionInterface {

  /**
   * The class name.
   *
   * @var string
   */
  protected $name;

  /**
   * The class namespace.
   *
   * @var string
   */
  protected $namespace;

  /**
   * The properties of this class.
   *
   * @var array
   */
  protected $properties = [];

  /**
   * @param string $name
   *   The class name.
   * @param string $namespace
   *   The class namespace.
   * @param array $properties
   *   The properties of this class.
   */
  function __construct($name, $namespace, array $properties) {
    $this->name = $name;
    $this->namespace = $namespace;
    $this->properties = $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->name;
  }

  /**
   * {@inheritdoc}
   */
  public function getNamespace() {
    return $this->namespace;
  }

  /**
   * {@inheritdoc}
   */
  public function getProperties() {
    return $this->properties;
  }

  /**
   * {@inheritdoc}
   */
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

  /**
   * A helper function to ease exception catching in the __toString() method.
   *
   * @return string
   */
  protected function toString() {
    $doc = RootNode::create($this->getNamespace());
    $class = ClassNode::create($this->getName());
    $constructor = ClassMethodNode::create('__construct');
    $class->appendMethod($constructor);

    foreach ($this->getProperties() as $name => $info) {
      $class->createProperty($name, isset($info['default']) ? $info['default'] : NULL, 'protected');
      if (isset($info['description'])) {
        $docString = "@var {$info['type']} $name\n  {$info['description']}";
      }
      else {
        $docString = "@var {$info['type']} $name";
      }
      $class->getProperty($name)->closest(Filter::isInstanceOf('\Pharborist\Objects\ClassMemberListNode'))->setDocComment(DocCommentNode::create($docString));
      $constructor->appendParameter(ParameterNode::create($name));
      $expression = Parser::parseSnippet("\$this->{$name} = \$$name;");
      $constructor->getBody()->lastChild()->before($expression);
      $getter = ClassMethodNode::create('get' . ucfirst($name));
      $class->appendMethod($getter);
      $class->getMethod('get' . ucfirst($name))->setDocComment(DocCommentNode::create("Gets the $name value."));
      $getter_expression = Parser::parseSnippet("return \$this->{$name};");
      $getter->getBody()->lastChild()->before(($getter_expression));
    }

    $doc->getNamespace($this->getNamespace())->getBody()->append($class);
    /* @todo dispatch an event to allow subscribers to alter $doc */
    $formatter = FormatterFactory::getPsr2Formatter();
    $formatter->format($doc->getNamespace($this->getNamespace()));
    return $doc->getText();
  }

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    try {
      return $this->toString();
    }
    /* @todo throw a custom exception */
    catch (\Exception $e) {
      print_r($e);
      return "\n";
    }
  }
}
