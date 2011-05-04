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
     * @var \ObjectSerializer\ArrayAdapter
     */
    private $arrayAdapter;

    /**
     * Adpater to use for serializing
     *
     * @param Adapter $adapter
     */
    public function __construct(ArrayAdapter $arrayAdapter, Adapter $adapter)
    {
        $this->arrayAdapter = $arrayAdapter;
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

        $asArray = $this->arrayAdapter->toArray($object);
        $data = $this->adapter->serialize($asArray);

        return $this->postSerialize($data);
    }

    /**
     * Unserializing serialized data based on adapter configuration
     *
     * @param array $data
     * @return object
     */
    public function unserialize($data)
    {
        $array = $this->adapter->unserialize($data);
        return $this->arrayAdapter->toObject($array);
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