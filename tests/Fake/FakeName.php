<?php

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdoInterface;
use Ray\Di\Di\Named;

class FakeName
{
    public $pdo;

    /**
     * @Named("log_db")
     */
    public function __construct(ExtendedPdoInterface $pdo)
    {
        $this->pdo = $pdo;
    }
}
