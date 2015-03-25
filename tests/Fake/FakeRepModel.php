<?php

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdoInterface;
use Aura\Sql\PdoInterface;

class FakeRepModel
{
    /**
     * @var PdoInterface
     */
    public $pdo;

    public function __construct(ExtendedPdoInterface $pdo)
    {
        $this->pdo = $pdo;
    }
}
