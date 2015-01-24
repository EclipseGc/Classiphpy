<?php
/**
 * @file
 * Contains DefaultPhpOutput.php.
 */

namespace Classiphpy\Output;

use Classiphpy\Input\InputInterface;
use Classiphpy\Definition\DefinitionInterface;

class DefaultPhpOutput implements OutputInterface {

  /**
   * @var string
   */
  protected $outputDirectory;

  /**
   * @param string $outputDirectory
   */
  function __construct($outputDirectory) {
    $this->outputDirectory = $outputDirectory;
  }


  public function writeOut(InputInterface $input) {
    $this->verify();
    $classes = $input->getClasses();
    foreach ($classes as $class_id => $definition) {
      $resource = fopen($this->getOutputDir() . '/' . "$class_id.php", 'w');
      fwrite($resource, $this->getTemplateOutput($class_id, $definition));
    }
  }

  public function verify() {
    $dir = $this->getOutputDir();
    if (file_exists($dir) && is_writable($dir) && !is_file($dir)) {
      return TRUE;
    }
    return FALSE;
  }

  public function getOutputDir() {
    return $this->outputDirectory;
  }

  public function getTemplateOutput($class_id, DefinitionInterface $definition) {
    $template = "<?php\n\n";
    // Get class namespace.
    if ($definition->getNamespace()) {
      $template .= "namespace " . $definition->getNamespace() . "\n\n";
    }
    // Get appropriate use statements.
    foreach ($definition->getUseStatements() as $class) {
      $template .= "use $class\n";
    }
    $template .= "\n";
    $template .= "class $class_id {\n";
    // Get class properties.
    foreach ($definition->getProperties() as $property) {
      $template .= "    protected \$$property";
    }
    // Generate constructor.
    $template .= "    public function __construct(" . implode(', ', $definition->getProperties()) . ") {\n";
    foreach ($definition->getProperties() as $property) {
      $template .= "        \$this->$property = \$$property;";
    }
    $template .= "    }\n";
    // End constructor.
    // Create getters.
    foreach ($definition->getProperties() as $property) {
      $template .= "    public function get". ucfirst($property) ."() {\n";
      $template .= "        return \$this->$property;\n";
      $template .= "    }\n";
    }
    // Close class.
    $template .= "}\n";
    return $template;
  }

} 