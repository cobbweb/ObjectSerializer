<?php

namespace Cob\ObjectSerializer\Tests;

use Cob\ObjectSerializer\Adapter\ArrayAdapter,
    Cob\ObjectSerializer\ObjectSerializer;

class ObjectSerializerTest extends \PHPUnit_Framework_TestCase
{

    public function testBasicSerializing()
    {
        $basic = new Basic('Testing');
        $adapter = new ArrayAdapter();

        $os = new ObjectSerializer($adapter);

        $expects = array(
            'name' => 'Testing',
            '_anotherProtected' => 'withValue',
            '_anotherVar' => 'defaultValue',
            'protectedVar' => null,
            'view' => null
        );

        $this->assertEquals($expects, $os->serialize($basic));
    }

    public function testBasicUnserializing()
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
        $os = new ObjectSerializer($adapter);

        $expects = new Basic('Unserialize');
        $expects->setAnotherProtected('withValue');
        $expects->setAnotherVar('someValue');
        $expects->view = new View('test');
        $expects->setProtectedVar(null);

        $this->assertEquals($expects, $os->unserialize($data));
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