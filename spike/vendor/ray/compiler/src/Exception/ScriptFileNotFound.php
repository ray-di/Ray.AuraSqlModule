<?php

declare(strict_types=1);

namespace Ray\Compiler\Exception;

use RuntimeException;

final class ScriptFileNotFound extends RuntimeException implements ExceptionInterface
{
}
