<?php

namespace Ray\Di\Compiler;

$instance = new \Ray\AuraSqlModule\TransactionalInterceptor($singleton('Aura\\Sql\\ExtendedPdoInterface-'));
$is_singleton = true;
return $instance;
