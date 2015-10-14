<?php

// load
use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = require dirname(__DIR__) . '/vendor/autoload.php';
/** @var $loader \Composer\Autoload\ClassLoader */
AnnotationRegistry::registerLoader([$loader, 'loadClass']);

$_ENV['TMP_DIR'] = __DIR__.'/tmp';
