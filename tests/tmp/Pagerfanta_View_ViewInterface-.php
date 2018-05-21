<?php

namespace Ray\Di\Compiler;

$instance = new \Pagerfanta\View\DefaultView($prototype('Pagerfanta\\View\\Template\\TemplateInterface-'));
$is_singleton = false;
return $instance;
