<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Annotation;

use Attribute;
use Ray\Di\Di\Qualifier;

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
