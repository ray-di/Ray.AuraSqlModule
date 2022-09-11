<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Ray\AuraSqlModule\Annotation\EnvDsn;
use Ray\Di\ProviderInterface;

use function getenv;

/**
 * @implements ProviderInterface<string>
 */
class AuthDsnProvider implements ProviderInterface
{
    private string $envKey;

    /**
     * @EnvDsn
     */
    #[EnvDsn]
    public function __construct(string $envKey)
    {
        $this->envKey = $envKey;
    }

    /**
     * {@inheritdoc}
     */
    public function get(): string
    {
        static $value;


        return getenv($this->envKey);
    }
}
