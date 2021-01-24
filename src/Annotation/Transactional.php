<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Annotation;

use Attribute;
use Doctrine\Common\Annotations\NamedArgumentConstructorAnnotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class Transactional implements NamedArgumentConstructorAnnotation
{
    /**
     * @var ?array<string>
     * @deprecated
     */
    public $value;

    /**
     * @param array<string> $value
     */
    public function __construct(array $value = ['pdo'])
    {
        $this->value = $value;
    }
}
