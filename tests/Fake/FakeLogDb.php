<?php

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdoInterface;
use Doctrine\Common\Annotations\Annotation\Target;
use Ray\Di\Di\Named;
use Ray\Di\Di\Qualifier;

/**
 * @Annotation
 * @Target("METHOD")
 * @Qualifier()
 */
final class FakeLogDb
{
}
