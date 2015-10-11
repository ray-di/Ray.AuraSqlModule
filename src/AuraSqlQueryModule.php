<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdoInterface;
use Aura\SqlQuery\Common\DeleteInterface;
use Aura\SqlQuery\Common\InsertInterface;
use Aura\SqlQuery\Common\SelectInterface;
use Aura\SqlQuery\Common\UpdateInterface;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Ray\AuraSqlModule\Annotation\AuraSqlConfig;
use Ray\AuraSqlModule\Annotation\AuraSqlQueryConfig;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

class AuraSqlQueryModule extends AbstractModule
{
    private $db;

    /**
     * @param string $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bind()->annotatedWith(AuraSqlQueryConfig::class)->toInstance($this->db);
        $this->bind(SelectInterface::class)->toProvider(AuraSqlQuerySelectProvider::class);
        $this->bind(InsertInterface::class)->toProvider(AuraSqlQueryInsertProvider::class);
        $this->bind(UpdateInterface::class)->toProvider(AuraSqlQueryUpdateProvider::class);
        $this->bind(DeleteInterface::class)->toProvider(AuraSqlQueryDeleteProvider::class);
    }
}
