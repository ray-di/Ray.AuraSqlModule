<?php

namespace Ray\Di\Compiler;

$instance = new \Aura\Sql\ExtendedPdo('sqlite::memory:', '', '', array(), array());
$is_singleton = true;
return $instance;
