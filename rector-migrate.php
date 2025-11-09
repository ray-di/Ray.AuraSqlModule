<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;

/**
 * Rector configuration for migrating from Doctrine Annotations to PHP 8 Attributes
 *
 * This configuration helps users migrate their code from doctrine/annotations
 * to native PHP 8 attributes for Ray.AuraSqlModule annotations.
 *
 * Usage:
 *   # Process a single directory
 *   vendor/bin/rector process src --config=vendor/ray/aura-sql-module/rector-migrate.php --dry-run
 *   vendor/bin/rector process src --config=vendor/ray/aura-sql-module/rector-migrate.php
 *
 *   # Process multiple directories
 *   vendor/bin/rector process src tests --config=vendor/ray/aura-sql-module/rector-migrate.php
 */
return RectorConfig::configure()
    ->withConfiguredRule(
        AnnotationToAttributeRector::class,
        [
            // Ray.AuraSqlModule Annotations
            new AnnotationToAttribute('Ray\AuraSqlModule\Annotation\Transactional'),
            new AnnotationToAttribute('Ray\AuraSqlModule\Annotation\WriteConnection'),
            new AnnotationToAttribute('Ray\AuraSqlModule\Annotation\ReadOnlyConnection'),
            new AnnotationToAttribute('Ray\AuraSqlModule\Annotation\Read'),
            new AnnotationToAttribute('Ray\AuraSqlModule\Annotation\AuraSql'),
            new AnnotationToAttribute('Ray\AuraSqlModule\Annotation\EnvAuth'),
            new AnnotationToAttribute('Ray\AuraSqlModule\Annotation\HttpMethod'),
            new AnnotationToAttribute('Ray\AuraSqlModule\Annotation\PagerViewOption'),
            new AnnotationToAttribute('Ray\AuraSqlModule\Annotation\AuraSqlQueryConfig'),
        ]
    );
