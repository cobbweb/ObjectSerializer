# ObjectSerializer

A lightweight, adapter based library for serializing and unserializing PHP objects. Features getter/setter detection
and event hooks for high customisation.

### Features

 * (Un)serializing nested objects
 * Whitelisting and Blacklisting variables to serialize

### Todo

 * Write adapters XML
 * Doctrine 2 and Doctrine ODM integration (adapters for Entities and Documents)
 * Refactor adapter configuration
 * Event system
 * Working with collections
 * How to handle deep class hierarchies

## Usage

ObjectSerializer is made of 3 main components:

 1. ArrayAdapter - responsible for converting an object to an array (and vice-versa)
 2. Adapter - responsible for serializing and unserializing arrays to various formats (e.g. JSON, XML, etc)
 3. SerializerManager - Bring the above together in a simple interface

At this stage I have only written a simple ArrayAdapter (called ObjectAdapter) for dealing with objects. There a two
data adapters, a JSON adapter and an ArrayAdapter (made for testing, does nothing to the input/output).

Writing an array adapter or data adapter is simple, just look at the provided interfaces and the internals of the
existing adapters and you should get the idea.

### Serialization

    <?php

    class BasicClass
    {

        private $myVar;

        public function __construct($myVar)
        {
            $this->myVar = $myVar;
        }

        private function getMyVar()
        {
            return $this->myVar;
        }

    }

    $arrayAdapter = new \ObjectSerializer\ArrayAdapter\ObjectAdapter(array('unserialize' => 'BasicClass'));
    $adapter = new \ObjectSerializer\Adapter\ArrayAdapter(); // or use JsonAdapter
    $sm = new \ObjectSerializer\SerializerManager($arrayAdapyer, $adapter);
    $basicClass = new BasicClass('Serialize Me!')

    $asArray = $sm->serialize($basicClass); // Returns: array('myVar' => 'Serialize Me!')

### Unserialization

    <?php

    // ... continuing on from the above example

    $data = array('myVar' => 'Unserialize Me!');

    $basicClass = $sm->unserialize($data);
    echo $basicClass->getMyVar(); // Echoes: Unserialize Me!

### Unserializing nested class

    <?php

    class SubClass extends BasicClass {}

    $config = array(
        'unserialize' => array(
            'BasicClass' => array(
                'myVar' => 'BasicClass'
            )
        )
    );

    $arrayAdapter = new \ObjectSerializer\ArrayAdapter\ObjectAdapter($config);
    $adapter = new \ObjectSerializer\Adapter\JsonAdapter();
    $sm = new \ObjectSerializer\SerializerManager($arrayAdapter, $adapter);

    $json = '{"myVar":{"myVar":"I'm an instance of SubClass"}';

    $basicClass = $sm->unserialize($json);
    echo $basicClass->getMyVar()->getMyVar() // Echoes: I'm an instance of SubClass