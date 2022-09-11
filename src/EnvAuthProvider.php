<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Ray\AuraSqlModule\Annotation\EnvAuth;
use Ray\Di\ProviderInterface;
use Ray\Di\SetContextInterface;

use function assert;
use function getenv;

/**
 * @implements ProviderInterface<string>
 */
class EnvAuthProvider implements ProviderInterface, SetContextInterface
{
    private string $context;

    /** @var array<string, string>  */
    private array $envAuth;

    /** @param string $context */
    public function setContext($context): void
    {
        $this->context = $context;
    }

    /**
     * @param array<string, string> $envAuth
     *
     * @EnvAuth
     */
    #[EnvAuth]
    public function __construct(array $envAuth)
    {
        $this->envAuth = $envAuth;
    }

    /**
     * {@inheritdoc}
     */
    public function get(): string
    {
        $value = (string) getenv($this->envAuth[$this->context]);
        assert($value);

        return $value;
    }
}
