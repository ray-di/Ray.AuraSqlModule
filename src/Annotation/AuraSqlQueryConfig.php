<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Annotation;

use Attribute;
use Ray\Di\Di\Qualifier;

/**
 * Qualifies the database type that the Aura.SqlQuery builders are created for
 */
#[Attribute(Attribute::TARGET_PARAMETER), Qualifier]
final class AuraSqlQueryConfig
{
    /**
     * @param array<string, string>|null $value
     */
    public function __construct(public ?array $value = null)
    {
    }
}
