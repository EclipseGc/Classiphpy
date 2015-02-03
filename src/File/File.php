<?php
/**
 * @file
 * Contains File.php.
 */

namespace Classiphpy\File;


/**
 * Class File
 * @package Classiphpy\File
 *
 * A class which represent the name, extension, path and contents of a file.
 *
 * @todo Determine if this class warrants an interface.
 */
class File {

  /**
   * @var string;
   */
  protected $name;

  /**
   * @var
   */
  protected $ext;

  /**
   * @var string;
   */
  protected $path;

  /**
   * @var string;
   */
  protected $contents;

  function __construct($name, $ext, $path) {
    $this->name = $name;
    $this->ext = $ext;
    $this->path = $path;
  }

  public function getName() {
    return $this->name;
  }

  public function getFileName() {
    return $this->getName() . '.' . $this->getExt();
  }

  public function getExt() {
    return $this->ext;
  }

  public function getPath() {
    return $this->path;
  }

  public function getContents() {
    return $this->contents;
  }

  public function setContents($contents) {
    $this->contents = $contents;
  }

  public function getFullFilePath() {
    return $this->getPath() . DIRECTORY_SEPARATOR . $this->getFileName();
  }
} 