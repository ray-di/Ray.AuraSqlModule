<?php
namespace Ray\AuraSqlModule\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
final class Transactional
{
    /**
     * @var array<string>
     *
     * @deprecated
     */
    public $value = ['pdo'];
}
