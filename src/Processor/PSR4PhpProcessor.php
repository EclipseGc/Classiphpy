<?php
/**
 * @file
 * Contains ComposerPhpProcessor.php.
 */

namespace Classiphpy\Processor;

use Classiphpy\Definition\DefinitionInterface;
use Classiphpy\File\File;

class PSR4PhpProcessor implements ProcessorInterface {

  /**
   * @var string
   */
  protected $libraryDir;

  public function __construct($library_dir) {
    if (!is_string($library_dir)) {
      throw new \Exception('Library directory must be a string.');
    }
    $this->libraryDir = $library_dir;
  }

  /**
   * {@inheritdoc}
   */
  public function process(array $classes, array $data) {
    $files = [];
    $namespace = $data['defaults']['namespace'];
    foreach ($classes as $class) {
      /** @var \Classiphpy\Definition\DefinitionInterface $class */
      if (!strpos($class->getNamespace(), $namespace) === 0) {
        throw new \Exception(sprintf('The %s namespace is not within the %s class', $namespace, $class->getNamespace()));
      }

      $file = new File($class->getName(), 'php', $this->getDirectory($class, $namespace));
      $file->setContents((string) $class);
      $files[$class->getNamespace() . '\\' . $class->getName()] = $file;
    }
    return $files;
  }

  protected function getDirectory(DefinitionInterface $class, $namespace) {
    $dir = substr($class->getNamespace(), strlen($namespace) +1);
    return $this->libraryDir . DIRECTORY_SEPARATOR . str_replace("\\", DIRECTORY_SEPARATOR, $dir);
  }

}