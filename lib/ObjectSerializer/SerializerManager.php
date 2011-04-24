<?php

namespace ObjectSerializer;

/**
 * Serializes and unserializes data using various data type adapters
 *
 * @author Andrew Cobby <cobby@cobbweb.me>
 */
class SerializerManager
{

    /**
     * @var \ObjectSerializer\Adapter
     */
    private $adapter;

    /**
     * Adpater to use for serializing
     *
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Serialize and object with the adapter
     *
     * @param  $object
     * @return mixed
     */
    public function serialize($object)
    {
        $this->preSerialize($object);

        $data = $this->adapter->serialize($object);

        return $this->postSerialize($data);
    }

    /**
     * Unserializing serialized data based on adapter configuration
     *
     * @param array $data
     * @return object
     */
    public function unserialize(array $data)
    {
        return $this->adapter->unserialize($data);
    }

    /**
     * preSerializing event hook
     *
     * @param object $object Object being serialized
     * @return object
     */
    private function preSerialize($object)
    {
        return $object;
    }

    /**
     * postSerialize event hook
     *
     * @param mixed $data Serialized data
     * @return mixed
     */
    private function postSerialize($data)
    {
        return $data;
    }

    /**
     * preUnserialize event hook
     *
     * @param mixed $data Serialized data
     * @return mixed
     */
    private function preUnserialize($data)
    {
        return $data;
    }

    /**
     * postUnserialize event hook
     *
     * @param object $object Unserialized object
     * @return object
     */
    private function postUnserialize($object)
    {
        return $object;
    }

}