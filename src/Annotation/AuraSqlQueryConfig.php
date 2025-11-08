<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Annotation;

use Attribute;
use Ray\Di\Di\Qualifier;

#[Attribute(Attribute::TARGET_METHOD), Qualifier]
final class AuraSqlQueryConfig
{
    /** @var ?array<string, string> */
    public $value;

    /**
     * @param array<string, string> $value
     */
    public function __construct(?array $value = null)
    {
        $this->value = $value;
    }
}
