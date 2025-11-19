<?php

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdoInterface;
use Ray\Di\Di\Inject;
use Ray\Di\Di\Named;

class FakeName
{
    public $pdo;
    public $pdoAnno;
    public $pdoSetterInject;

    public function __construct(
        #[Named('log_db')]
        ExtendedPdoInterface $pdo
    ) {
        $this->pdo = $pdo;
    }

    #[Inject]
    public function setFakeDb(
        #[FakeLogDb]
        ExtendedPdoInterface $pdo
    ) {
        $this->pdoAnno = $pdo;
    }

    #[FakeLogDbInject]
    public function setFakeDbWithInjectAnnotation(
        #[FakeLogDbInject]
        ExtendedPdoInterface $pdo
    ) {
        $this->pdoSetterInject = $pdo;
    }
}
