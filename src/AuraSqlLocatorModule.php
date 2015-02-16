<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Ray\AuraSqlModule\Annotation\AuraSql;
use Ray\AuraSqlModule\Annotation\Read;
use Ray\AuraSqlModule\Annotation\Write;
use Ray\Di\AbstractModule;

class AuraSqlLocatorModule extends AbstractModule
{

    /**
     * @var ConnectionLocatorInterface
     */
    private $connectionLocator;

    /**
     * @var array
     */
    private $readMethods;

    /**
     * @var array
     */
    private $writeMethods;

    public function __construct(
        ConnectionLocatorInterface $connectionLocator = null,
        array $readMethods = [],
        array $writeMethods = []
    ) {
        AnnotationRegistry::registerFile(__DIR__ . '/DoctrineAnnotations.php');
        $this->connectionLocator = $connectionLocator;
        $this->readMethods = $readMethods;
        $this->writeMethods = $writeMethods;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        if ($this->readMethods && $this->writeMethods) {
            $this->bind()->annotatedWith(Read::class)->toInstance($this->readMethods);
            $this->bind()->annotatedWith(Write::class)->toInstance($this->writeMethods);
        }
        if ($this->connectionLocator) {
            $this->bind(ConnectionLocatorInterface::class)->toInstance($this->connectionLocator);
        }
        $methods = array_merge($this->readMethods, $this->writeMethods);
        $this->bindInterceptor(
            $this->matcher->annotatedWith(AuraSql::class),
            new IsInMethodMatcher($methods),
            [AuraSqlConnectionInterceptor::class]
        );
    }
}
