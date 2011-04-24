# ObjectSerializer

A lightweight, adapter based library for serializing and unserializing PHP objects. Features getter/setter detection
and event hooks for high customisation.

### Features

 * (Un)serializing nested objects
 * Whitelisting and Blacklisting variables to serialize

### Todo

 * Refactor over structure
 * Write adapters for JSON and XML
 * Doctrine 2 integration
 * Refactor adapter configuration
 * Event system
 * Working with collections

## Usage

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

    $adapter = new \ObjectSerializer\Adapter\ArrayAdapter(array('unserialize' => 'BasicClass'));
    $sm = new \ObjectSerializer\SerializerManager($adapter);
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

    $adapter = new \ObjectSerializer\Adapter\ArrayAdapter($config);
    $sm = new \ObjectSerializer\SerializerManager($adapter);

    $data = array(
        'myVar' => array(
            'myVar' => "I'm an instance of SubClass"
        )
    );

    $basicClass = $sm->unserialize($data);
    echo $basicClass->getMyVar()->getMyVar() // Echoes: I'm an instance of SubClass