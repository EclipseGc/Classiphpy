<?php
/**
 * @file
 * Contains ComposerPhpProcessor.php.
 */

namespace Classiphpy\Processor;

use Classiphpy\Definition\DefinitionInterface;
use Classiphpy\File\File;

/**
 * Class PSR4PhpProcessor
 * @package Classiphpy\Processor
 *
 * The PSR4PhpProcessor builds a series of files based upon an array of
 * DefinitionInterface(s) and puts them in a relative dir to the given
 * $library_dir in the constructor. This will parse namespaces, and remove the
 * elements normally used in PSR-0 in favor of a PSR-4 approach.
 */
class PSR4PhpProcessor implements ProcessorInterface {

  /**
   * @var string
   *   The relative directory to which we expect to ultimately write files.
   */
  protected $libraryDir;

  /**
   * @param string $library_dir
   *   The relative directory to which we expect to ultimately write files.
   * @throws \Exception
   */
  public function __construct($library_dir) {
    if (!is_string($library_dir)) {
      /* @todo Write custom exception class. */
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

  /**
   * Gets the relative directory to which a file should be written.
   *
   * @param DefinitionInterface $class
   *   The class for which to define a directory location.
   * @param $namespace
   *   The base PSR-4 namespace.
   * @return string
   *   The relative directory location.
   */
  protected function getDirectory(DefinitionInterface $class, $namespace) {
    $dir = substr($class->getNamespace(), strlen($namespace) +1);
    return $this->libraryDir . DIRECTORY_SEPARATOR . str_replace("\\", DIRECTORY_SEPARATOR, $dir);
  }

}