<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Annotation;

use Attribute;

/**
 * Runs the method in a database transaction and rolls back when a PDOException is thrown
 *
 * @see https://bearsunday.github.io/manuals/1.0/en/database_aura.html#transactions
 * @see \Ray\AuraSqlModule\TransactionalInterceptor
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class Transactional
{
    /**
     * @param array<string> $value
     */
    public function __construct(
        /**
         * @deprecated
         */
        public array $value = ['pdo']
    )
    {
    }
}
