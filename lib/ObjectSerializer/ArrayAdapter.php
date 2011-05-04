<?php

namespace ObjectSerializer;

interface ArrayAdapter 
{

    public function toArray($object);

    public function toObject($data);

}
