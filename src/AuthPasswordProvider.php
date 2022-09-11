<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Ray\AuraSqlModule\Annotation\EnvPassword;
use Ray\Di\ProviderInterface;

use function getenv;

/**
 * @implements ProviderInterface<string>
 */
class AuthPasswordProvider implements ProviderInterface
{
    private string $envKey;

    /**
     * @EnvPassword
     */
    #[EnvPassword]
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
