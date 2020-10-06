<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
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
