<?php

namespace ObjectSerializer\Adapter;

use ObjectSerializer\Adapter;
 
class ArrayAdapter implements Adapter
{

    public function serialize(array $data)
    {
        return $data;
    }

    public function unserialize($data)
    {
        return $data;
    }

}