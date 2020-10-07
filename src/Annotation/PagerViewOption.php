<?php
namespace Ray\AuraSqlModule\Annotation;

use Ray\Di\Di\Qualifier;

/**
 * @Annotation
 * @Target("METHOD")
 * @Qualifier
 */
final class PagerViewOption
{
    /**
     * @var string
     */
    public $value;
}
