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
    public function __construct(ExtendedPdoInterface $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @Inject
     * @FakeLogDb
     */
    public function setFakeDb(ExtendedPdoInterface $pdo)
    {
        $this->pdoAnno = $pdo;
    }

    /**
     * @FakeLogDbInject
     */
    public function setFakeDbWithInjectAnnotation(ExtendedPdoInterface $pdo)
    {
        $this->pdoSetterInject = $pdo;
    }
}
