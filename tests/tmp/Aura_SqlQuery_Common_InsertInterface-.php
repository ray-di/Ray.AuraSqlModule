<?php

namespace Ray\Di\Compiler;

$instance = new \Ray\AuraSqlModule\AuraSqlQueryInsertProvider('sqlite');
$is_singleton = false;
return $instance->get();
