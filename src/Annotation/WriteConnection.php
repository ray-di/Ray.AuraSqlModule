<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class WriteConnection
{
}
