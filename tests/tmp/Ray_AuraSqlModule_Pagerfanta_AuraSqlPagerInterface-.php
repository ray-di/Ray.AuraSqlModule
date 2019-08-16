<?php

namespace Ray\Di\Compiler;

$instance = new \Ray\AuraSqlModule\Pagerfanta\AuraSqlPager($prototype('Pagerfanta\\View\\ViewInterface-'), array());
$is_singleton = false;
return $instance;
