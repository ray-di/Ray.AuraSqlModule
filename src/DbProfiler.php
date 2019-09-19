<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdoInterface;
use Aura\Sql\Profiler;
use Psr\Log\LoggerInterface;
use Ray\Di\InjectorInterface;

final class DbProfiler
{
    /**
     * @var ExtendedPdoInterface
     */
    private $pdo;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(InjectorInterface $injector)
    {
        $this->pdo = $injector->getInstance(ExtendedPdoInterface::class);
        $this->logger = $injector->getInstance(LoggerInterface::class);
    }

    public function setup() : void
    {
        $this->pdo->setProfiler(new Profiler);
        $this->pdo->getProfiler()->setActive(true);
    }

    public function tearDown() : void
    {
        $profiles = $this->pdo->getProfiler()->getProfiles();
        foreach ($profiles as &$profile) {
            unset($profile['trace'], $profile['duration']);
        }
        unset($profile);
        if ($profiles) {
            $this->logger->debug('sql:', $profiles);
        }
    }
}
