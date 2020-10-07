<?php
namespace Ray\AuraSqlModule\Annotation;

use Ray\Di\Di\Qualifier;

/**
 * @Annotation
 * @Target("METHOD")
 * @Qualifier
 */
final class AuraSqlQueryConfig
{
    /**
     * @var array<string, string>
     */
    public $value;
}
