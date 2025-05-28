<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Override;
use Ray\AuraSqlModule\Pagerfanta\AuraSqlPagerModule;
use Ray\Di\AbstractModule;

use function preg_match;

class AuraSqlBaseModule extends AbstractModule
{
    public function __construct(private readonly string $dsn, ?AbstractModule $module = null)
    {
        parent::__construct($module);
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    protected function configure(): void
    {
        // @Transactional
        $this->install(new TransactionalModule());
        $this->install(new AuraSqlPagerModule());
        preg_match(AuraSqlModule::PARSE_PDO_DSN_REGEX, $this->dsn, $parts);
        $dbType = $parts[1] ?? '';
        $this->install(new AuraSqlQueryModule($dbType));
    }
}
