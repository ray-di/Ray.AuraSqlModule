<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Ray\AuraSqlModule\Annotation\EnvUser;
use Ray\Di\ProviderInterface;

use function getenv;

/**
 * @implements ProviderInterface<string>
 */
class AuthUserProvider implements ProviderInterface
{
    private string $envKey;

    /**
     * @EnvUser
     */
    #[EnvUser]
    public function __construct(string $envKey)
    {
        $this->envKey = $envKey;
    }

    /**
     * {@inheritdoc}
     */
    public function get(): string
    {
        return getenv($this->envKey);
    }
}
