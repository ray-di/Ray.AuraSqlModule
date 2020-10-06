<?php

namespace Ray\Di\Compiler;

$instance = new \Ray\AuraSqlModule\AuraSqlQueryDeleteProvider('sqlite');
$isSingleton = false;
return $instance->get();
