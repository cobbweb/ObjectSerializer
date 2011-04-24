<?php

namespace Cob\ObjectSerializer;

interface Adapter 
{

    public function serialize($object);

    public function unserialize($data);

}
