<?php

namespace Ray\AuraSqlModule;

use Attribute;
use Aura\Sql\ExtendedPdoInterface;
use Ray\Di\Di\InjectInterface;
use Ray\Di\Di\Named;
use Ray\Di\Di\Qualifier;
use Ray\Di\InjectorInterface;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_PARAMETER), Qualifier]
final class FakeLogDbInject implements InjectInterface
{
    public $optional = true;

    public function isOptional()
    {
        return $this->optional;
    }
}
