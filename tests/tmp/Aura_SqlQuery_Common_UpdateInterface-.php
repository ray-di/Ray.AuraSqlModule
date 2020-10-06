<?php

namespace Ray\Di\Compiler;

$instance = new \Ray\AuraSqlModule\AuraSqlQueryUpdateProvider('sqlite');
$isSingleton = false;
return $instance->get();
