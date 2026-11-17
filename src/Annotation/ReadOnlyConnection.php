<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Annotation;

use Attribute;

/**
 * Sets the `pdo` property to the read (slave) connection before the method runs
 *
 * @see https://bearsunday.github.io/manuals/1.0/en/database_aura.html#replication
 * @see \Ray\AuraSqlModule\AuraSqlSlaveDbInterceptor
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class ReadOnlyConnection
{
}
