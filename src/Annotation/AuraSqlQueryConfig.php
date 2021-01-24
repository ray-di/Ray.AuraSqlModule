<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Annotation;

use Attribute;
use Doctrine\Common\Annotations\NamedArgumentConstructorAnnotation;
use Ray\Di\Di\Qualifier;

/**
 * @Annotation
 * @Target("METHOD")
 * @Qualifier
 */
#[Attribute(Attribute::TARGET_METHOD), Qualifier]
final class AuraSqlQueryConfig implements NamedArgumentConstructorAnnotation
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
