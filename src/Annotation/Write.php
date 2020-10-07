<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Annotation;

use Ray\Di\Di\Qualifier;

/**
 * @Annotation
 * @Target("METHOD")
 * @Qualifier
 */
final class Write
{
    /** @var string */
    public $value;
}
