<?php

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdo;
use Aura\Sql\ExtendedPdoInterface;
use Ray\AuraSqlModule\Annotation\AuraSql;
use Ray\AuraSqlModule\Annotation\ReadOnlyConnection;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\AuraSqlModule\Annotation\WriteConnection;
use Ray\Di\Di\Named;

class FakeMultiDb
{
    protected $pdo1;
    protected $pdo2;
    protected $pdo3;

    /**
     * @Named("pdo1=pdo1,pdo2=pdo2,pdo3=pdo3")
     */
    public function __construct(ExtendedPdoInterface $pdo1, ExtendedPdoInterface $pdo2, ExtendedPdoInterface $pdo3)
    {
        $this->pdo1 = $pdo1;
        $this->pdo2 = $pdo2;
        $this->pdo3 = $pdo3;
        $this->pdo1->exec('CREATE TABLE user(name, age)');
        $this->pdo2->exec('CREATE TABLE user(name, age)');
        $this->pdo3->exec('CREATE TABLE user(name, age)');
    }

    /**
     * @Transactional({"pdo1","pdo2","pdo3"})
     */
    public function write()
    {
        $stmt1 = $this->pdo1->prepare('INSERT INTO user (name, age) VALUES (?, ?)');
        $stmt2 = $this->pdo2->prepare('INSERT INTO user (name, age) VALUES (?, ?)');
        $stmt3 = $this->pdo3->prepare('INSERT INTO user (name, age) VALUES (?, ?)');
        $stmt1->execute(['koriym', 18]);
        $stmt2->execute(['ray', 19]);
        $stmt3->execute(['bear', 20]);
    }

    public function read()
    {
        $users = [];
        $users[] = $this->pdo1->fetchAll('SELECT * FROM user');
        $users[] = $this->pdo2->fetchAll('SELECT * FROM user');
        $users[] = $this->pdo3->fetchAll('SELECT * FROM user');

        return $users;
    }
}
