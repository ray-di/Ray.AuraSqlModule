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

    /**
     * @Named("log_db")
     */
    public function __construct(#[Named('log_db')] ExtendedPdoInterface $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @Inject
     * @FakeLogDb
     */
    #[Inject]
    public function setFakeDb(#[FakeLogDb] ExtendedPdoInterface $pdo)
    {
        $this->pdoAnno = $pdo;
    }

    /**
     * @FakeLogDbInject
     */
    #[FakeLogDbInject]
    public function setFakeDbWithInjectAnnotation(ExtendedPdoInterface $pdo)
    {
        $this->pdoSetterInject = $pdo;
    }
}
