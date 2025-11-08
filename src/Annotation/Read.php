<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Annotation;

use Attribute;
use Ray\Di\Di\Qualifier;

#[Attribute(Attribute::TARGET_METHOD|Attribute::TARGET_PARAMETER), Qualifier]
final class Read
{
    public function __construct(public string $value = '')
    {
    }
}
