<?php

namespace ObjectSerializer\Tests;

use ObjectSerializer\ArrayAdapter\ObjectAdapter,
    ObjectSerializer\Adapter\ArrayAdapter,
    ObjectSerializer\SerializerManager,
    ObjectSerializer\Adapter\JsonAdapter;

class ObjectSerializerTest extends \PHPUnit_Framework_TestCase
{

    public function testBasicSerializing()
    {
        $basic = new Basic('Testing');
        $objectAdapter = new ObjectAdapter();
        $adapter = new ArrayAdapter();
        $sm = new SerializerManager($objectAdapter, $adapter);

        $expects = array(
            'name' => 'Testing',
            '_anotherProtected' => 'withValue',
            '_anotherVar' => 'defaultValue',
            'protectedVar' => null,
            'view' => null
        );

        $this->assertEquals($expects, $sm->serialize($basic));
    }

    public function testBasicUnserializing()
    {
        $data = array(
            'name' => 'Testing',
            '_anotherProtected' => 'withValue',
            '_anotherVar' => 'defaultValue',
            'protectedVar' => null,
            'view' => null
        );

        $objectAdapter = new ObjectAdapter(array('unserialize' => __NAMESPACE__ . '\Basic'));
        $adapter = new ArrayAdapter();
        $sm = new SerializerManager($objectAdapter, $adapter);
        $basic = $sm->unserialize($data);

        $expects = new Basic('Testing');
        $expects->setAnotherProtected('withValue');
        $expects->setAnotherVar('defaultValue');
        $expects->setProtectedVar(null);
        $expects->view = null;

        $this->assertEquals($expects, $basic);
    }

    public function testNestedSerializing()
    {
        $basic = new Basic('Testing 2');
        $basic->view = new View('my/path');

        $objectAdapter = new ObjectAdapter();
        $adapter = new ArrayAdapter();
        $sm = new SerializerManager($objectAdapter, $adapter);

        $expects = array(
            'name' => 'Testing 2',
            '_anotherProtected' => 'withValue',
            '_anotherVar' => 'defaultValue',
            'protectedVar' => '',
            'view' => array(
                'scriptPath' => 'my/path'
            )
        );

        $this->assertEquals($expects, $sm->serialize($basic));
    }

    public function testNestedUnserializing()
    {
        $data = array(
            'name' => 'Unserialize',
            '_anotherProtected' => 'withValue',
            '_anotherVar' => 'someValue',
            'protectedVar' => null,
            'view' => array(
                'scriptPath' => 'test'
            )
        );

        $objectAdapter = new ObjectAdapter(array(
                'unserialize' => array(
                    __NAMESPACE__ . '\Basic' => array(
                        'view' => __NAMESPACE__ . '\View'
                    )
                )
        ));

        $adapter = new ArrayAdapter();
        
        $sm = new SerializerManager($objectAdapter, $adapter);

        $expects = new Basic('Unserialize');
        $expects->setAnotherProtected('withValue');
        $expects->setAnotherVar('someValue');
        $expects->view = new View('test');
        $expects->setProtectedVar(null);

        $this->assertEquals($expects, $sm->unserialize($data));
    }

    public function testSimpleJsonSerializing()
    {
        $simple = new Simple;
        $simple->name = "Hello!";

        $arrayAdapter = new ObjectAdapter();
        $adapter = new JsonAdapter();
        $sm = new SerializerManager($arrayAdapter, $adapter);

        $expects = '{"name":"Hello!"}';
        $this->assertEquals($expects, $sm->serialize($simple));
    }

    public function testSimpleJsonUnserializing()
    {
        $json = '{"name":"Hello World!"}';
        $arrayAdapter = new ObjectAdapter(array('unserialize' => __NAMESPACE__ . '\Simple'));
        $adapter = new JsonAdapter();
        $sm = new SerializerManager($arrayAdapter, $adapter);

        $simple = new Simple;
        $simple->name = "Hello World!";

        $this->assertEquals($simple, $sm->unserialize($json));
    }

}

class Simple
{

    public $name;

}

class Basic
{

    private $name;

    private $_anotherVar = 'defaultValue';

    protected $protectedVar;

    protected $_anotherProtected = 'withValue';

    public $view;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function setAnotherVar($anotherVar)
    {
        $this->_anotherVar = $anotherVar;
    }

    public function getAnotherVar()
    {
        return $this->_anotherVar;
    } 

    public function setAnotherProtected($anotherProtected)
    {
        $this->_anotherProtected = $anotherProtected;
    }

    public function getAnotherProtected()
    {
        return $this->_anotherProtected;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setProtectedVar($protectedVar)
    {
        $this->protectedVar = $protectedVar;
    }

    public function getProtectedVar()
    {
        return $this->protectedVar;
    }

}


class View
{

    protected $scriptPath;

    public function __construct($scriptPath)
    {
        $this->scriptPath = $scriptPath;
    }

    public function setScriptPath($scriptPath)
    {
        $this->scriptPath = $scriptPath;
    }

    public function getScriptPath()
    {
        return $this->scriptPath;
    }

}