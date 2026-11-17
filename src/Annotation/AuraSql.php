<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Annotation;

use Attribute;

/**
 * Sets the `pdo` property to the read or write connection by the name of the called method
 *
 * @see \Ray\AuraSqlModule\AuraSqlConnectionInterceptor
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class AuraSql
{
}
