<?php
/**
 * @file
 * Contains DefaultPhpOutput.php.
 */

namespace Classiphpy\Output;


use Classiphpy\Input\InputInterface;

class DefaultPhpOutput {

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


  public function echoOut(InputInterface $input) {
    $this->verify();
    $classes = $input->getClasses();
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

} 