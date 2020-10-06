<?php

namespace Ray\Di\Compiler;

$instance = new \Ray\AuraSqlModule\TransactionalInterceptor($singleton('Aura\\Sql\\ExtendedPdoInterface-'));
$isSingleton = true;
return $instance;
