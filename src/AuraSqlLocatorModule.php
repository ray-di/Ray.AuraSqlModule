<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Ray\AuraSqlModule\Annotation\AuraSql;
use Ray\AuraSqlModule\Annotation\Read;
use Ray\AuraSqlModule\Annotation\ReadOnlyConnection;
use Ray\AuraSqlModule\Annotation\Write;
use Ray\AuraSqlModule\Annotation\WriteConnection;
use Ray\Di\AbstractModule;

class AuraSqlLocatorModule extends AbstractModule
{
    /**
     * @var ConnectionLocatorInterface
     */
    private $connectionLocator;

    /**
     * @var string[]
     */
    private $readMethods;

    /**
     * @var string[]
     */
    private $writeMethods;

    public function __construct(
        ConnectionLocatorInterface $connectionLocator,
        array $readMethods = [],
        array $writeMethods = [],
        AbstractModule $module = null
    ) {
        $this->connectionLocator = $connectionLocator;
        $this->readMethods = $readMethods;
        $this->writeMethods = $writeMethods;
        parent::__construct($module);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        if ((bool) $this->readMethods && (bool) $this->writeMethods) {
            $this->bind()->annotatedWith(Read::class)->toInstance($this->readMethods);
            $this->bind()->annotatedWith(Write::class)->toInstance($this->writeMethods);
        }
        $this->bind(ConnectionLocatorInterface::class)->toInstance($this->connectionLocator);
        $methods = \array_merge($this->readMethods, $this->writeMethods);
        // @AuraSql
        $this->installLocatorDb($methods);
        // @ReadOnlyConnection @WriteConnection
        $this->installReadWriteConnection();
        // @Transactional
        $this->install(new TransactionalModule);
    }

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

    /**
     * @param string[] $methods
     */
    private function installLocatorDb(array $methods)
    {
        // locator db
        $this->bindInterceptor(
            $this->matcher->annotatedWith(AuraSql::class), // @AuraSql in class
            $this->matcher->logicalAnd(
                new IsInMethodMatcher($methods),
                $this->matcher->logicalNot(
                    $this->matcher->annotatedWith(ReadOnlyConnection::class)
                ),
                $this->matcher->logicalNot(
                    $this->matcher->annotatedWith(Connection::class)
                )
            ),
            [AuraSqlConnectionInterceptor::class]
        );
    }
}
