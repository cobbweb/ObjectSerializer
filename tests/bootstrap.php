<?php

set_include_path(
    implode(PATH_SEPARATOR, array(
        realpath('../lib'),
        realpath('../lib/vendor/doctrine-common/lib'),
        get_include_path()
    ))
);

require_once 'Doctrine/Common/ClassLoader.php';

use Doctrine\Common\ClassLoader;

$loader = new ClassLoader("Cob\ObjectSerializer");
$loader->register();

$loader = new ClassLoader("Doctrine\Common");
$loader->register();

$loader = new ClassLoader("Cob\ObjectSerializer\Tests", './Cob/ObjectSerializer/Tests');
$loader->register();