<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdo;
use Aura\Sql\ExtendedPdoInterface;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

class AuraSqlMasterModule extends AbstractModule
{
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
    private $password;

    /**
     * @var array
     */
    private $options;

    /**
     * @var array
     */
    private $attributes;

    public function __construct(string $dsn, string $user = '', string $password = '', array $options = [], array $attributes = [], AbstractModule $module = null)
    {
        $this->dsn = $dsn;
        $this->user = $user;
        $this->password = $password;
        $this->options = $options;
        $this->attributes = $attributes;
        parent::__construct($module);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bind(ExtendedPdoInterface::class)->toConstructor(ExtendedPdo::class, 'dsn=pdo_dsn,username=pdo_user,password=pdo_pass,options=pdo_option,attributes=pdo_attributes')->in(Scope::SINGLETON);
        $this->bind()->annotatedWith('pdo_dsn')->toInstance($this->dsn);
        $this->bind()->annotatedWith('pdo_user')->toInstance($this->user);
        $this->bind()->annotatedWith('pdo_pass')->toInstance($this->password);
        $this->bind()->annotatedWith('pdo_option')->toInstance($this->options);
        $this->bind()->annotatedWith('pdo_attributes')->toInstance($this->attributes);
    }
}
