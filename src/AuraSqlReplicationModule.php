<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Aura\Sql\ExtendedPdoInterface;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Ray\AuraSqlModule\Annotation\ReadOnlyConnection;
use Ray\AuraSqlModule\Annotation\WriteConnection;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

class AuraSqlReplicationModule extends AbstractModule
{
    /**
     * @var ConnectionLocatorInterface
     */
    private $connectionLocator;

    public function __construct(
        ConnectionLocatorInterface $connectionLocator = null
    ) {
        $this->connectionLocator = $connectionLocator;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        if ($this->connectionLocator) {
            $this->bind(ConnectionLocatorInterface::class)->toInstance($this->connectionLocator);
        }
        // ReadOnlyConnection when GET, otherwise WriteConnection
        $this->bind(ExtendedPdoInterface::class)->toProvider(AuraSqlReplicationDbProvider::class)->in(Scope::SINGLETON);
        // @ReadOnlyConnection @WriteConnection
        $this->installReadWriteConnection();
        // @Transactional
        $this->install(new TransactionalModule);
    }

    /**
     * @return void
     */
    protected function installReadWriteConnection()
    {
        // @ReadOnlyConnection
        $this->bindInterceptor(
            $this->matcher->any(),
            $this->matcher->annotatedWith(ReadOnlyConnection::class),
            [AuraSqlSlaveDbInterceptor::class]
        );
        // @WriteConnection
        $this->bindInterceptor(
            $this->matcher->any(),
            $this->matcher->annotatedWith(WriteConnection::class),
            [AuraSqlMasterDbInterceptor::class]
        );
    }
}
