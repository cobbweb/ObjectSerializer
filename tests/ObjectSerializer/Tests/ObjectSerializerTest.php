<?php

namespace ObjectSerializer\Tests;

use ObjectSerializer\Adapter\ArrayAdapter,
    ObjectSerializer\SerializerManager;

class ObjectSerializerTest extends \PHPUnit_Framework_TestCase
{

    public function testBasicSerializing()
    {
        $basic = new Basic('Testing');
        $adapter = new ArrayAdapter();

        $sm = new SerializerManager($adapter);

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

        $adapter = new ArrayAdapter(array('unserialize' => __NAMESPACE__ . '\Basic'));
        $sm = new SerializerManager($adapter);
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

        $adapter = new ArrayAdapter();
        $sm = new SerializerManager($adapter);

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

        $adapter = new ArrayAdapter(array(
                'unserialize' => array(
                    __NAMESPACE__ . '\Basic' => array(
                        'view' => __NAMESPACE__ . '\View'
                    )
                )
        ));
        
        $sm = new SerializerManager($adapter);

        $expects = new Basic('Unserialize');
        $expects->setAnotherProtected('withValue');
        $expects->setAnotherVar('someValue');
        $expects->view = new View('test');
        $expects->setProtectedVar(null);

        $this->assertEquals($expects, $sm->unserialize($data));
    }

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