<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Annotation;

use Attribute;

/**
 * @Annotation
 * @Target("CLASS")
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class AuraSql
{
}
