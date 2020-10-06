<?php

namespace Ray\Di\Compiler;

$instance = new \Ray\AuraSqlModule\Pagerfanta\AuraSqlPager($prototype('Pagerfanta\\View\\ViewInterface-'), array());
$isSingleton = false;
return $instance;
