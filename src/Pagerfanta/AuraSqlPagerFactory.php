<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;

class AuraSqlPagerFactory implements AuraSqlPagerFactoryInterface
{
    /**
     * @var AuraSqlPagerInterface
     */
    private $auraSqlPager;

    public function __construct(AuraSqlPagerInterface $auraSqlPager)
    {
        $this->auraSqlPager = $auraSqlPager;
    }

    /**
     * @inheritdoc
     */
    public function newInstance(ExtendedPdoInterface $pdo, $sql, $paging, $uriTemplate)
    {
        $this->auraSqlPager->init($pdo, $sql, $paging, new DefaultRouteGenerator($uriTemplate));

        return $this->auraSqlPager;
    }
}
