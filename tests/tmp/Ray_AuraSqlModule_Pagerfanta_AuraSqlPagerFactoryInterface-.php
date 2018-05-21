<?php

namespace Ray\Di\Compiler;

$instance = new \Ray\AuraSqlModule\Pagerfanta\AuraSqlPagerFactory($prototype('Ray\\AuraSqlModule\\Pagerfanta\\AuraSqlPagerInterface-'));
$is_singleton = false;
return $instance;
