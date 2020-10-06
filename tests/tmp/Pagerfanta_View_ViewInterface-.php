<?php

namespace Ray\Di\Compiler;

$instance = new \Pagerfanta\View\DefaultView($prototype('Pagerfanta\\View\\Template\\TemplateInterface-'));
$isSingleton = false;
return $instance;
