<?php

declare(strict_types=1);
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
require \dirname(__DIR__) . '/vendor/autoload.php';

\array_map('unlink', (array) \glob(__DIR__ . '/tmp/*.{php,txt}', GLOB_BRACE));
