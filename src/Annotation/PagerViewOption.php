<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Annotation;

use Attribute;
use Ray\Di\Di\Qualifier;

/**
 * Qualifies the options passed to the Pagerfanta view that renders the pager HTML
 *
 * @see https://bearsunday.github.io/manuals/1.0/en/database_aura.html#pagination
 */
#[Attribute(Attribute::TARGET_PARAMETER), Qualifier]
final class PagerViewOption
{
    public function __construct(public string $value = '')
    {
    }
}
