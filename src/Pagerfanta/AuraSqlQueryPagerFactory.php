<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use Aura\SqlQuery\Common\SelectInterface;

class AuraSqlQueryPagerFactory implements AuraSqlQueryPagerFactoryInterface
{
    /**
     * @var AuraSqlQueryPagerInterface
     */
    private $auraSqlQueryPager;

    public function __construct(AuraSqlQueryPagerInterface $auraSqlQueryPager)
    {
        $this->auraSqlQueryPager = $auraSqlQueryPager;
    }

    /**
     * {@inheritdoc}
     */
    public function newInstance(ExtendedPdoInterface $pdo, SelectInterface $select, $paging, $uriTemplate)
    {
        $this->auraSqlQueryPager->init($pdo, $select, $paging, new DefaultRouteGenerator($uriTemplate));

        return $this->auraSqlQueryPager;
    }
}
