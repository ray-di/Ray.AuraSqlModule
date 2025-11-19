<?php

namespace Ray\AuraSqlModule;

use Attribute;
use Aura\Sql\ExtendedPdoInterface;
use Ray\Di\Di\Named;
use Ray\Di\Di\Qualifier;

#[Attribute(Attribute::TARGET_PARAMETER), Qualifier]
final class FakeLogDb
{
}
