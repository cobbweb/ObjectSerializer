<?php

namespace Cob\ObjectSerializer\Adapter;

use Cob\ObjectSerializer\Adapter,
    Cob\ObjectSerializer\ObjectSerializer;
 
class ArrayAdapter implements Adapter
{

    protected $options = array();

    public function __construct(array $options = array())
    {
        $this->options = $options;
    }

    public function unserialize($data)
    {
        if(!isset($this->options['unserialize'])){
            // TODO: Change exception class
            throw new \Exception('No unserialize configuration');
        }

        if(is_array($this->options['unserialize'])){
            $baseClass = key($this->options['unserialize']);
            $subSerialize = $this->options['unserialize'][$baseClass];
        }else if(is_string($this->options['unserialize'])){
            $baseClass = $this->options['unserialize'];
        }else{
            throw new \Exception('Invalid unseralize class');
        }

        $reflClass = new \ReflectionClass($baseClass);
        $object = unserialize(sprintf('O:%d:"%s":0:{}', strlen($baseClass), $baseClass));

        foreach($data as $key => $value){
            // if whitelisting
            if(isset($this->options['include']) && !in_array($key, $this->options['include'])){
                continue;
            }

            // if blacklisting
            if(isset($this->options['exclude']) && in_array($key, $this->options['exclude'])){
                continue;
            }

            if(isset($subSerialize) && isset($subSerialize[$key])){
                $adapter = new ArrayAdapter(array(
                        'unserialize' => $subSerialize[$key]
                ));
                $os = new ObjectSerializer($adapter);
                $value = $os->unserialize($value);
            }

            $reflProp = $reflClass->getProperty($key);
            $reflProp->setAccessible(true);
            $reflProp->setValue($object, $value);
        }

        return $object;
    }

    public function serialize($object)
    {
        $class = new \ReflectionClass($object);
        $data = array();

        foreach($class->getProperties() as $property){
            // if whitelisting
            if(isset($this->options['include']) && !in_array($property->getName(), $this->options['include'])){
                continue;
            }

            // if blacklisting
            if(isset($this->options['exclude']) && in_array($property->getName(), $this->options['exclude'])){
                continue;
            }

            $property->setAccessible(true);
            $value = $property->getValue($object);

            if(is_object($value)){
                $value = $this->serialize($value);
            }

            $data[$property->getName()] = $value;
        }

        return $data;
    }

    public function getOptions()
    {
        return $this->options;
    }

}