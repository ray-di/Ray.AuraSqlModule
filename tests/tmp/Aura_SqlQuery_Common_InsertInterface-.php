<?php

namespace Ray\Di\Compiler;

$instance = new \Ray\AuraSqlModule\AuraSqlQueryInsertProvider('sqlite');
$isSingleton = false;
return $instance->get();
