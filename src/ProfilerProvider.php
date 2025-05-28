<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\Profiler\Profiler;
use Override;
use Psr\Log\LoggerInterface;
use Ray\Di\Provider;

class ProfilerProvider implements Provider
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    #[Override]
    public function get(): Profiler
    {
        $profiler = new Profiler($this->logger);
        $profiler->setLogFormat('{duration}: {function} {statement}:{values}');
        $profiler->setActive(true);

        return $profiler;
    }
}
