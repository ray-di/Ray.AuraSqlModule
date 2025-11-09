<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdo;
use Aura\Sql\ExtendedPdoInterface;
use Override;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;
use SensitiveParameter;

final class AuraSqlMasterModule extends AbstractModule
{
    /**
     * @phpstan-param array<string, mixed> $options
     * @phpstan-param array<string, mixed> $attributes
     */
    public function __construct(
        private readonly string $dsn,
        private readonly string $user = '',
        #[SensitiveParameter]
        private readonly string $password = '',
        /** @var array<string, mixed> */
        private readonly array $options = [],
        /** @var array<string, mixed> */
        private readonly array $attributes = [],
        ?AbstractModule $module = null
    ) {
        parent::__construct($module);
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    protected function configure(): void
    {
        $this->bind(ExtendedPdoInterface::class)->toConstructor(
            ExtendedPdo::class,
            'dsn=pdo_dsn,username=pdo_user,password=pdo_pass,options=pdo_option,attributes=pdo_attributes',
        )->in(Scope::SINGLETON);
        $this->bind()->annotatedWith('pdo_dsn')->toInstance($this->dsn);
        $this->bind()->annotatedWith('pdo_user')->toInstance($this->user);
        $this->bind()->annotatedWith('pdo_pass')->toInstance($this->password);
        $this->bind()->annotatedWith('pdo_option')->toInstance($this->options);
        $this->bind()->annotatedWith('pdo_attributes')->toInstance($this->attributes);
    }
}
