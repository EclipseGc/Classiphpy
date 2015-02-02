# Classiphpy
Classiphpy is a PHP library designed to read definition data from sources such as json or yaml, and generate class structures accordingly. Classiphpy thinks of this process in simple terms of InputInterfaces and OutputInterfaces. InputInterfaces simply describe the expectations of the data you are parsing, validating this and getting it ready for output via a Classiphpy\Definition\DefinitionInterface object. OutputInterfaces simply interpret the definitions generated in the the InputInterface and attempt to write out classes to a spec. All of these object are simple interfaces and you should expect to write custom Input/Output and Definition implementations for each new input/output use case.

##Example
The DefaultJsonInput class included in Classiphpy expects an input along these lines:

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

Run through the DefaultPhpOutput class and this json would generate the following PHP.

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
In the above example, to actually bootstrap this, we'd only need to instantiate our Inputs and Outputs properly and run them through Classiphpy. Assuming our json was already in $json that would look thus:

```php
<?php

$output = new \Classiphpy\Output\DefaultOutput('/tmp/classiphpy');
$test = new Classiphpy(['\Classiphpy\Definition\DefaultJsonDefinition'], $output);
$processor = new \Classiphpy\Processor\PSR4PhpProcessor('src');
$test->build($data, $processor);

?>
```
