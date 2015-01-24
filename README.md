# Classiphpy
Classiphpy is a PHP library designed to read definition data from sources such as json or yaml, and generate class structures accordingly. Classiphpy thinks of this process in simple terms of InputInterfaces and OutputInterfaces. InputInterfaces simply describe the expectations of the data you are parsing, validating this and getting it ready for output via a Classiphpy\Definition\DefinitionInterface object. OutputInterfaces simply interpret the definitions generated in the the InputInterface and attempt to write out classes to a spec. All of these object are simple interfaces and you should expect to write custom Input/Output and Definition implementations for each new input/output use case.

##Example
The DefaultJsonInput class included in Classiphpy expects an input along these lines:

```javascript
{
  "defaults":{
    "namespace":"EclipseGc"
  },
  "classes":{
    "Person":{
      "properties":[
        "first",
        "last"
      ],
      "namespace":"EclipseGc\\Person"
    }
  }
}
```

Run through the DefaultPhpOutput class and this json would generate the following PHP.

```php
<?php

namespace EclipseGc\Person


class Person {

    protected $first

    protected $last

    public function __construct(first, last) {
        $this->first = $first;
        $this->last = $last;
    }

    public function getFirst() {
        return $this->first;
    }

    public function getLast() {
        return $this->last;
    }

}
```

##Getting started
In the above example, to actually bootstrap this, we'd only need to instantiate our Inputs and Outputs properly and run them through Classiphpy. Assuming our json was already in $json that would look thus:

```php
<?php

$input = new \Classiphpy\Input\DefaultJsonInput('\Classiphpy\Definition\DefaultJsonDefinition');
$output = new \Classiphpy\Output\DefaultPhpOutput('/tmp/classiphpy');
$test = new Classiphpy($input, $output);

$test->build($json);

?>
```
