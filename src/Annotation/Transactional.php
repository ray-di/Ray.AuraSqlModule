<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Transactional
{
    /**
     * @param array<string> $value
     */
    public function __construct(
        /**
         * @deprecated
         */
        public array $value = ['pdo']
    )
    {
    }
}
