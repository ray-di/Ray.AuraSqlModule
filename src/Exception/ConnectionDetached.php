<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Exception;

use LogicException;

/** An adapter or fetcher restored by unserialize() has no connection and cannot query. */
final class ConnectionDetached extends LogicException
{
}
