<?php

namespace Ray\Di\Compiler;

$instance = new \Ray\AuraSqlModule\Pagerfanta\AuraSqlQueryPagerFactory($prototype('Ray\\AuraSqlModule\\Pagerfanta\\AuraSqlQueryPagerInterface-'));
$is_singleton = false;
return $instance;
