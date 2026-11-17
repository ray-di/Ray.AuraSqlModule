<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Annotation;

use Attribute;

/**
 * Sets the `pdo` property to the read or write connection for the methods
 * listed in the read and write method lists of AuraSqlLocatorModule
 *
 * @see \Ray\AuraSqlModule\AuraSqlConnectionInterceptor
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class AuraSql
{
}
