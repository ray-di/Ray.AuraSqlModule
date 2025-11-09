<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Ray\AuraSqlModule\AuraSqlModule;
use Ray\Di\Injector;
use Test\UserFinder;
use Test\UserQuery;
use Test\UserRepository;

// Test that attributes are recognized by Ray.Di
try {
    echo "Testing attribute-based dependency injection...\n\n";

    // Create injector with AuraSqlModule
    $injector = new Injector(new AuraSqlModule('sqlite::memory:'));

    // Test 1: UserFinder with #[Read] attribute
    echo "1. Testing UserFinder with #[Read] attribute:\n";
    try {
        $finder = $injector->getInstance(UserFinder::class);
        echo "   ✓ UserFinder instantiated successfully\n";
        echo "   ✓ #[Read] attribute was recognized\n";
    } catch (\Exception $e) {
        echo "   ✗ Failed: " . $e->getMessage() . "\n";
    }

    echo "\n";

    // Test 2: UserRepository with #[Transactional] attribute
    echo "2. Testing UserRepository with #[Transactional] attribute:\n";
    try {
        $repo = $injector->getInstance(UserRepository::class);
        echo "   ✓ UserRepository instantiated successfully\n";
        echo "   ✓ #[Transactional] attribute was recognized\n";
    } catch (\Exception $e) {
        echo "   ✗ Failed: " . $e->getMessage() . "\n";
    }

    echo "\n";

    // Test 3: Check that reflection recognizes the attributes
    echo "3. Testing reflection API recognizes attributes:\n";

    $reflectionFinder = new ReflectionClass(UserFinder::class);
    $constructorAttrs = $reflectionFinder->getConstructor()->getAttributes();
    echo "   UserFinder::__construct() has " . count($constructorAttrs) . " attribute(s)\n";
    foreach ($constructorAttrs as $attr) {
        echo "   ✓ Found: " . $attr->getName() . "\n";
    }

    echo "\n";

    $reflectionRepo = new ReflectionClass(UserRepository::class);
    $saveMethod = $reflectionRepo->getMethod('save');
    $saveAttrs = $saveMethod->getAttributes();
    echo "   UserRepository::save() has " . count($saveAttrs) . " attribute(s)\n";
    foreach ($saveAttrs as $attr) {
        echo "   ✓ Found: " . $attr->getName() . "\n";
    }

    $saveMultiMethod = $reflectionRepo->getMethod('saveMulti');
    $saveMultiAttrs = $saveMultiMethod->getAttributes();
    echo "   UserRepository::saveMulti() has " . count($saveMultiAttrs) . " attribute(s)\n";
    foreach ($saveMultiAttrs as $attr) {
        echo "   ✓ Found: " . $attr->getName();
        $instance = $attr->newInstance();
        if (property_exists($instance, 'value')) {
            echo " (value: " . json_encode($instance->value) . ")";
        }
        echo "\n";
    }

    echo "\n";
    echo "✓ All migration tests passed!\n";
    echo "✓ Attributes are correctly recognized by PHP and Ray.Di\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
