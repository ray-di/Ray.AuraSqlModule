<?php

namespace Ray\AuraSqlModule;

use Attribute;
use Aura\Sql\ExtendedPdoInterface;
use Doctrine\Common\Annotations\Annotation\Target;
use Ray\Di\Di\InjectInterface;
use Ray\Di\Di\Named;
use Ray\Di\Di\Qualifier;
use Ray\Di\InjectorInterface;

/**
 * @Annotation
 * @Target("METHOD")
 * @Qualifier()
 */
#[Attribute(Attribute::TARGET_METHOD), Qualifier]
final class FakeLogDbInject implements InjectInterface
{
    public $optional = true;

    public function isOptional()
    {
        return $this->optional;
    }
}
