<?php
/**
 * @file
 * Contains OutputInterface.php.
 */
namespace Classiphpy\Output;

/**
 * Interface OutputInterface
 * @package Classiphpy\Output
 *
 * Output Interface objects take an array of File objects and write them to
 * their final destination. This could be disk, or screen, or any other storage
 * mechanism.
 */
interface OutputInterface {

  /**
   * Write out the file objects to their destination.
   *
   * @param \Classiphpy\File\File[] $files
   *   The array of classes to output.
   */
  public function writeOut(array $files);

  /**
   * Verify if this OutputInterface can operate.
   *
   * @return boolean
   */
  public function verify();
}
