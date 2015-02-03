# Classiphpy
Classiphpy is a PHP library designed to read definition data, and generate corresponding class structures. Classiphpy thinks of this process in terms of a DefinitionInterface and a ProcessorInterface. DefinitionInterfaces describe the expectations of the data you are parsing, validating this and providing an array of DefinitionInterface object. ProcessorInterface interpret the definitions generated in the the DefinitionInterface and generate File class data to match. Individual use cases should be described as 1 or more DefinitionInterface classes to ready your data for processing. The file definitions generated during processing and then available to be written to disk via the DefaultOutput class, or you may write another Output class as necessary for non-file-writing use-cases.

##Example
The DefaultDefinition class included in Classiphpy expects an input along these lines:

```php
<?php
$data = [
  "defaults" => [
    "namespace" => "EclipseGc"
  ],
  "classes" => [
    "Person" => [
      "properties" => [
        "first",
        "last"
      ],
      "namespace" => "EclipseGc\\Person",
    ],
    "Animal" => [
      "properties" => [
        "kingdom",
        "phylum",
        "genus",
        "species"
      ],
      "namespace" => "EclipseGc\\Animal",
    ]
  ]
];
```

Run through the PSR4PhpProcessor class and this json would generate the following PHP.

```php
<?php
namespace EclipseGc\Animal;

class Animal
{
    protected $species;

    protected $genus;

    protected $phylum;

    protected $kingdom;

    public function __construct($kingdom, $phylum, $genus, $species)
    {
        $this->kingdom = $kingdom;
        $this->phylum = $phylum;
        $this->genus = $genus;
        $this->species = $species;
    }

    public function getKingdom()
    {
        return $this->kingdom;
    }

    public function getPhylum()
    {
        return $this->phylum;
    }

    public function getGenus()
    {
        return $this->genus;
    }

    public function getSpecies()
    {
        return $this->species;
    }
}
```

##Getting started
In the above example, to actually bootstrap this, we'd only need to inform the Classiphpy class which definition(s) we wish to use, and hand it an instance of our Output class. We then instantiate a processor of our choosing (these could be nested with a decorator pattern to add additional files beyond what our DefinitionInterface classes are expected to generate) and Classiphpy generates the file structures and writes them to disk.

```php
<?php

$output = new \Classiphpy\Output\DefaultOutput('/tmp/classiphpy');
$test = new Classiphpy(['\Classiphpy\Definition\DefaultJsonDefinition'], $output);
$processor = new \Classiphpy\Processor\PSR4PhpProcessor('src');
$test->build($data, $processor);

?>
```
