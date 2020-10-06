<?php

namespace Ray\Di\Compiler;

$instance = new \Aura\Sql\ExtendedPdo('sqlite::memory:', '', '', array(), array());
$isSingleton = true;
return $instance;
