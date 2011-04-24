<?php

namespace Cob\ObjectSerializer;
 
class ObjectSerializer 
{

    /**
     * Object to be serialized
     *
     * @var object
     */
    private $object;

    /**
     * Data to be unserialized
     *
     * @var mixed
     */
    private $data;

    /**
     * @var \Cob\ObjectSerializer\Adapter
     */
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function serialize($object)
    {
        $this->preSerialize($object);

        $data = $this->adapter->serialize($object);

        return $this->postSerialize($data);
    }

    public function unserialize(array $data)
    {
        return $this->adapter->unserialize($data);
    }

    private function preSerialize($object)
    {
        return true;
    }

    private function postSerialize($data)
    {
        return $data;
    }

    private function preUnserialize($data)
    {
        return $data;
    }

    private function postUnserialize($object)
    {
        return true;
    }

}