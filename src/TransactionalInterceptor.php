<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdoInterface;
use Aura\Sql\PdoInterface;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;
use Ray\AuraSqlModule\Exception\InvalidTransactionalPropertyException;
use Ray\AuraSqlModule\Exception\RollbackException;

class TransactionalInterceptor implements MethodInterceptor
{
    /**
     * @var PdoInterface
     */
    private $pdo;

    public function __construct(ExtendedPdoInterface $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(MethodInvocation $invocation)
    {
        try {
            $this->pdo->beginTransaction();
            $result = $invocation->proceed();
            $this->pdo->commit();

            return $result;
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            throw new RollbackException($e->getMessage(), 0, $e);
        }
    }
}
//}
//    /**
//     * @param object $object the object having pdo
//     * @param string $prop   the name of pdo property
//     *
//     * @throws InvalidTransactionalPropertyException
//     *
//     * @return \Pdo
//     */
//    private function beginTransaction($object, $prop)
//    {
//        try {
//            $ref = new \ReflectionProperty($object, $prop);
//        } catch (\ReflectionException $e) {
//            throw new InvalidTransactionalPropertyException($prop, 0, $e);
//        }
//        $ref->setAccessible(true);
//        $db = $ref->getValue($object);
//        $db->beginTransaction();
//
//        return $db;
//    }
//}
