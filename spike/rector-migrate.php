<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;

/**
 * Rector configuration for migrating from Doctrine Annotations to PHP 8 Attributes
 */
return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
    ])
    ->withConfiguredRule(
        AnnotationToAttributeRector::class,
        [
            // Ray.AuraSqlModule Annotations
            new AnnotationToAttribute('Ray\AuraSqlModule\Annotation\Transactional'),
            new AnnotationToAttribute('Ray\AuraSqlModule\Annotation\Read'),
            new AnnotationToAttribute('Ray\AuraSqlModule\Annotation\AuraSqlQueryConfig'),
            new AnnotationToAttribute('Ray\AuraSqlModule\Annotation\PagerViewOption'),
        ]
    );
