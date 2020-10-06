<?php

namespace Ray\Di\Compiler;

$instance = new \Ray\AuraSqlModule\AuraSqlQuerySelectProvider('sqlite');
$isSingleton = false;
return $instance->get();
