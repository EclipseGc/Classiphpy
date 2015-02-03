<?php
/**
 * @file
 * Contains DefaultPhpOutput.php.
 */

namespace Classiphpy\Output;

/**
 * Class DefaultOutput
 * @package Classiphpy\Output
 *
 * Write File classes to disk.
 */
class DefaultOutput implements OutputInterface {

  /**
   * @var string
   *   The absolute directory to which files will be written.
   */
  protected $outputDirectory;

  /**
   * @param string $outputDirectory
   *   The absolute directory to which files will be written.
   */
  function __construct($outputDirectory) {
    $this->outputDirectory = $outputDirectory;
  }

  /**
   * {@inheritdoc}
   */
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

  /**
   * {@inheritdoc}
   */
  public function verify() {
    $dir = $this->getOutputDir();
    if (file_exists($dir) && is_writable($dir) && !is_file($dir)) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Helper function for getting the directory passed in the constructor.
   *
   * @return string
   *   The absolute directory to which files will be written.
   */
  protected function getOutputDir() {
    return $this->outputDirectory;
  }

}
