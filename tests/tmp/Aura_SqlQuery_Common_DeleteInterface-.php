<?php

namespace Ray\Di\Compiler;

$instance = new \Ray\AuraSqlModule\AuraSqlQueryDeleteProvider('sqlite');
$is_singleton = false;
return $instance->get();
