<?php

namespace ObjectSerializer\Adapter;

use ObjectSerializer\Adapter;

class JsonAdapter implements Adapter
{

    public function serialize(array $data)
    {
        return json_encode($data);
    }

    public function unserialize($data)
    {
        return json_decode($data);
    }

}