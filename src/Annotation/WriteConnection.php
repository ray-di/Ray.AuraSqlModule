<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Annotation;

use Attribute;

/**
 * Sets the `pdo` property to the write (master) connection before the method runs
 * when replication is configured (AuraSqlModule without a slave ignores it)
 *
 * @see https://bearsunday.github.io/manuals/1.0/en/database_aura.html#replication
 * @see \Ray\AuraSqlModule\AuraSqlMasterDbInterceptor
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class WriteConnection
{
}
