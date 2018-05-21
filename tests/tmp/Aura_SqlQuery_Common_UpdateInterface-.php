<?php

namespace Ray\Di\Compiler;

$instance = new \Ray\AuraSqlModule\AuraSqlQueryUpdateProvider('sqlite');
$is_singleton = false;
return $instance->get();
