<?php

declare(strict_types=1);

namespace Ray\Compiler\Exception;

use RuntimeException;

final class CompileLockFailed extends RuntimeException implements ExceptionInterface
{
}
