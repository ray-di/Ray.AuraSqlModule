<?php

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdo;
use Aura\Sql\ExtendedPdoInterface;
use Ray\Di\AbstractModule;

class NamedPdoModule extends AbstractModule
{
    /**
     * @var string
     */
    private $qualifer;

    /**
     * @var string
     */
    private $dsn;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $pass;

    /**
     * @param string $qualifer
     * @param string $dsn
     * @param string $user
     * @param string $pass
     */
    public function __construct($qualifer, $dsn, $user = '', $pass = '')
    {
        $this->qualifer = $qualifer;
        $this->dsn = $dsn;
        $this->user = $user;
        $this->pass = $pass;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bindNamedPdo($this->qualifer, $this->dsn, $this->user, $this->pass);
    }

    private function bindNamedPdo($qualifer, $dsn, $user, $pass)
    {
        $this->bind(ExtendedPdoInterface::class)
            ->annotatedWith($qualifer)
            ->toConstructor(
                ExtendedPdo::class,
                "dsn={$qualifer}_dsn,username={$qualifer}_username,password={$qualifer}_password"
            );
        $this->bind()->annotatedWith("{$qualifer}_dsn")->toInstance($dsn);
        $this->bind()->annotatedWith("{$qualifer}_username")->toInstance($user);
        $this->bind()->annotatedWith("{$qualifer}_password")->toInstance($pass);
    }
}
