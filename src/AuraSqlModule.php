<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdoInterface;
use Aura\SqlQuery\Common\SelectInterface;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Ray\AuraSqlModule\Annotation\AuraSqlConfig;
use Ray\AuraSqlModule\Annotation\Transactional;
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
        if ($dsn) {
            $this->bind()->annotatedWith(AuraSqlConfig::class)->toInstance([$dsn, $user, $password]);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bind(ExtendedPdoInterface::class)->toProvider(AuraSqlProvider::class)->in(Scope::SINGLETON);
        $this->bind(SelectInterface::class)->toProvider(AuraSqlQuerySelectProvider::class)->in(Scope::SINGLETON);
        // @Transactional
        $this->bindInterceptor(
            $this->matcher->any(),
            $this->matcher->annotatedWith(Transactional::class),
            [TransactionalInterceptor::class]
        );
        $this->install(new TransactionalModule);
    }
}
