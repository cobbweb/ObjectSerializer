<?php

namespace ObjectSerializer;

/**
 * Adapter interface
 *
 * @author Andrew Cobby <cobby@cobbweb.me>
 */
interface Adapter 
{

    /**
     * Serialize an object
     *
     * @abstract
     * @param object $object Object to serialize
     * @return void
     */
    public function serialize(array $data);

    /**
     * Unserialize an object with data
     *
     * @abstract
     * @param mixed $data Data to unserialize with
     * @return object
     */
    public function unserialize($data);

}
