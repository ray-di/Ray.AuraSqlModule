<?php

namespace Ray\Di\Compiler;

$instance = new \Ray\AuraSqlModule\AuraSqlQuerySelectProvider('sqlite');
$is_singleton = false;
return $instance->get();
