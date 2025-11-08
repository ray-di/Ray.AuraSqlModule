<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Annotation;

use Attribute;
use Ray\Di\Di\Qualifier;

#[Attribute(Attribute::TARGET_METHOD), Qualifier]
final class AuraSqlQueryConfig
{
    /**
     * @param array<string, string> $value
     */
    public function __construct(public ?array $value = null)
    {
    }
}
