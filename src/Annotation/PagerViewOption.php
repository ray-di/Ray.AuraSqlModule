<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Annotation;

use Attribute;
use Ray\Di\Di\Qualifier;

#[Attribute(Attribute::TARGET_METHOD), Qualifier]
final class PagerViewOption
{
    public function __construct(public string $value)
    {
    }
}
