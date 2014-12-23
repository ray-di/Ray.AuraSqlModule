<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdoInterface;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

class AuraSqlModule extends AbstractModule
{
    /**
     * @param string $dsn
     * @param string $user
     * @param string $password
     */
    public function __construct($dsn, $user = '', $password = '')
    {
        $this->bind()->annotatedWith('aura_sql_config')->toInstance([$dsn, $user, $password]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bind(ExtendedPdoInterface::class)->toProvider(AuraSqlProvider::class)->in(Scope::SINGLETON);
    }
}
