<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\Profiler\Profiler;
use Psr\Log\LoggerInterface;
use Ray\Di\Provider;

class ProfilerProvider implements Provider
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function get(): Profiler
    {
        $profiler = new Profiler($this->logger);
        $profiler->setLogFormat('{duration}: {function} {statement}:{values}');
        $profiler->setActive(true);

        return $profiler;
    }
}
