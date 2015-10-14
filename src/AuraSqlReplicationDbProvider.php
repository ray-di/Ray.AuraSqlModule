<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Ray\Di\ProviderInterface;

class AuraSqlReplicationDbProvider implements ProviderInterface
{
    /**
     * @var ConnectionLocatorInterface
     */
    private $connectionLocator;

    /**
     * @param ConnectionLocatorInterface $connectionLocator
     */
    public function __construct(ConnectionLocatorInterface $connectionLocator)
    {
        $this->connectionLocator = $connectionLocator;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        $isGetRequest = isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'GET';
        $pdo = $isGetRequest ? $this->connectionLocator->getRead() : $this->connectionLocator->getWrite();

        return $pdo;
    }
}
