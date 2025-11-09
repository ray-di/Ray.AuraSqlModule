<?php

declare(strict_types=1);

namespace Ray\Compiler;

use Doctrine\Common\Cache\CacheProvider;
use Override;
use Ray\Di\AbstractModule;
use Ray\Di\Annotation\ScriptDir;

/**
 * @psalm-import-type ScriptDir from Types
 * @psalm-import-type SavedSingletons from Types
 */
abstract class AbstractInjectorContext implements LazyModuleInterface
{
    /**
     * @var ScriptDir
     * @readonly
     */
    public $tmpDir;

    /** @param ScriptDir $tmpDir */
    public function __construct(string $tmpDir)
    {
        $this->tmpDir = $tmpDir;
    }

    #[Override]
    abstract public function __invoke(): AbstractModule;

    abstract public function getCache(): CacheProvider;

    /**
     * Return array of cacheable singleton class names
     *
     * @return SavedSingletons
     */
    public function getSavedSingleton(): array
    {
        return [];
    }
}
