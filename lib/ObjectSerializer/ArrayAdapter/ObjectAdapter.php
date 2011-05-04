<?php

namespace ObjectSerializer\ArrayAdapter;

use ObjectSerializer\ArrayAdapter,
    ObjectSerializer\SerializerManager;

/**
 * Adapter that serializes objects into arrays
 *
 * @author Andrew Cobby <cobby@cobbweb.me>
 * @throws \RuntimeException
 */
class ObjectAdapter implements ArrayAdapter
{

    /**
     * Configuration options
     *
     * @var array
     */
    protected $options = array();

    /**
     * @param array $options Configuration options
     */
    public function __construct(array $options = array())
    {
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException
     * @param array $data
     * @return object
     */
    public function toObject($data)
    {
        // unserialize configuration required
        if(!isset($this->options['unserialize'])){
            throw new \RuntimeException('No unserialize configuration');
        }

        // is array or string
        if(is_array($this->options['unserialize'])){
            $baseClass = key($this->options['unserialize']);
            $subSerialize = $this->options['unserialize'][$baseClass];
        }else if(is_string($this->options['unserialize'])){
            $baseClass = $this->options['unserialize'];
        }else{
            throw new \RuntimeException('Invalid unseralize class');
        }

        $reflClass = new \ReflectionClass($baseClass);
        $object = unserialize(sprintf('O:%d:"%s":0:{}', strlen($baseClass), $baseClass));

        // inject data into new object
        foreach($data as $key => $value){
            // if whitelisting
            if(isset($this->options['include']) && !in_array($key, $this->options['include'])){
                continue;
            }

            // if blacklisting
            if(isset($this->options['exclude']) && in_array($key, $this->options['exclude'])){
                continue;
            }

            // nested unserializing
            if(isset($subSerialize) && isset($subSerialize[$key])){
                $adapter = new ObjectAdapter(array(
                        'unserialize' => $subSerialize[$key]
                ));
                $value = $adapter->toObject($value);
            }

            $reflProp = $reflClass->getProperty($key);
            $reflProp->setAccessible(true);
            $reflProp->setValue($object, $value);
        }

        return $object;
    }

    /**
     * {@inheritdoc}
     *
     * @param object $object Object to serialize
     * @return array
     */
    public function toArray($object)
    {
        $class = new \ReflectionClass($object);
        $data = array();


        foreach($class->getProperties() as $property){
            if(isset($this->options['exclude']) && in_array($property->getName(), $this->options['exclude'])){
                continue;
            }

            $property->setAccessible(true);
            $value = $property->getValue($object);

            if(is_object($value)){
                $value = $this->toArray($value);
            }

            $data[$property->getName()] = $value;
        }

        return $data;
    }

    /**
     * Get adapter configuration
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

}