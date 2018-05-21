<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\Di\AbstractModule;

class TransactionalModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        // @Transactional
        $this->bindInterceptor(
            $this->matcher->any(),
            $this->matcher->annotatedWith(Transactional::class),
            [TransactionalInterceptor::class]
        );
    }
}
