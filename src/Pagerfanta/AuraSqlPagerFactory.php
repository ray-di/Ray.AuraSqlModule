<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use Pagerfanta\Exception\LogicException;

class AuraSqlPagerFactory implements AuraSqlPagerFactoryInterface, \ArrayAccess
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
    public function newInstance(ExtendedPdoInterface $pdo, $sql, array $params, $paging, $uriTemplate)
    {
        $this->auraSqlPager->init($pdo, $sql, $params, $paging, new DefaultRouteGenerator($uriTemplate));

        return $this->auraSqlPager;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        throw new LogicException('unsupported');
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->auraSqlPager->execute($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        throw new LogicException('read only');
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        throw new LogicException('read only');
    }
}
