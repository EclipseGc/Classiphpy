<?php
/**
 * @file
 * Contains ComposerProcessor.php.
 */

namespace Classiphpy\Processor;


use Classiphpy\File\File;

class ComposerProcessor implements ProcessorInterface {

  /**
   * @var ProcessorInterface
   */
  protected $processor;

  /**
   * @var string
   */
  protected $name;

  /**
   * @var string
   */
  protected $license;

  /**
   * @param ProcessorInterface $processor
   * @param $name
   * @param $license
   */
  public function __construct(ProcessorInterface $processor, $name, $license) {
    $this->processor = $processor;
    $this->name = $name;
    $this->license = $license;
  }

  protected function getAllowedKeys() {
    return [
      'name',
      'description',
      'version',
      'type',
      'keywords',
      'homepage',
      'time',
      'license',
      'authors',
      'support',
      'require',
      'require-dev',
      'conflict',
      'replace',
      'provide',
      'suggest',
      'autoload',
      'autoload-dev',
      'minimum-stability',
      'prefer-stable',
      'repositories',
      'config',
      'scripts',
      'extra',
      'bin',
      'archive',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function process(array $classes, array $data) {
    $file = new File('composer', 'json', '');
    /** @var \Classiphpy\Definition\DefinitionInterface $class */
    $dependencies = [];
    foreach ($classes as $class) {
      $dependencies += $class->getDependencies();
    }
    $composer = [
      'name' => $this->name,
      'license' => $this->license,
    ];
    if ($dependencies) {
      $composer['require'] = $dependencies;
    }
    $file->setContents(json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    $files = $this->processor->process($classes, $data);
    $files['composer'] = $file;
    return $files;
  }


} 