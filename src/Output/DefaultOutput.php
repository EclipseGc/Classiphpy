<?php
/**
 * @file
 * Contains DefaultPhpOutput.php.
 */

namespace Classiphpy\Output;

use Classiphpy\Input\InputInterface;
use Classiphpy\Definition\DefinitionInterface;

class DefaultOutput implements OutputInterface {

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

  public function writeOut(array $files) {
    if (!$this->verify()) {
      throw new \Exception('Your destination folder is either not writable or does not exist.');
    }
    foreach ($files as $file) {
      /** @var \Classiphpy\File\File $file */
      $dirs = explode(DIRECTORY_SEPARATOR, $file->getPath());
      $current_dir = $this->getOutputDir();
      foreach ($dirs as $dir) {
        $current_dir .=  DIRECTORY_SEPARATOR . $dir;
        if (!is_dir($current_dir)) {
          mkdir($current_dir);
        }
      }
      file_put_contents($this->getOutputDir() . DIRECTORY_SEPARATOR . $file->getFullFilePath(), $file->getContents());
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

  /**public function getTemplateOutput($class_id, DefinitionInterface $definition) {
    $template = $this->getTemplateHeader($class_id, $definition);
    $template .= $this->getTemplateClass($class_id, $definition);
    $template .= $this->getTemplateProperties($class_id, $definition);
    $template .= $this->getTemplateConstructor($class_id, $definition);
    $template .= $this->getTemplateGetters($class_id, $definition);
    $template .= $this->getTemplateExtras($class_id, $definition);
    $template .= $this->getTemplateFooter($class_id, $definition);
    return $template;
  }

  protected function getTemplateHeader($class_id, DefinitionInterface $definition) {
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
    return $template;
  }

  protected function getTemplateClass($class_id, DefinitionInterface $definition) {
    return "class $class_id {\n\n";
  }

  protected function getTemplateProperties($class_id, DefinitionInterface $definition) {
    // Get class properties.
    $template = '';
    foreach ($definition->getProperties() as $property) {
      $template .= "    protected \$$property\n\n";
    }
    return $template;
  }

  protected function getTemplateConstructor($class_id, DefinitionInterface $definition) {
    // Generate constructor.
    $template = "    public function __construct(\$" . implode(', $', $definition->getProperties()) . ") {\n";
    foreach ($definition->getProperties() as $property) {
      $template .= "        \$this->$property = \$$property;\n";
    }
    $template .= "    }\n\n";
    // End constructor.
    return $template;
  }

  protected function getTemplateGetters($class_id, DefinitionInterface $definition) {
    // Create getters.
    $template = '';
    foreach ($definition->getProperties() as $property) {
      $template .= "    public function get". ucfirst($property) ."() {\n";
      $template .= "        return \$this->$property;\n";
      $template .= "    }\n\n";
    }
    return $template;
  }

  protected function getTemplateExtras($class_id, DefinitionInterface $definition) {
    return '';
  }

  protected function getTemplateFooter($class_id, DefinitionInterface $definition) {
    // Close class.
    return "}\n";
  }*/

}
