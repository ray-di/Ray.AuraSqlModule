<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/src-files',
        __DIR__ . '/tests',
    ])
    ->withPhpSets()
    ->withTypeCoverageLevel(1)
    ->withDeadCodeLevel(1)
    ->withCodeQualityLevel(1);
