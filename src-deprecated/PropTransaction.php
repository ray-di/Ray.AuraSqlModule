<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdoInterface;
use Ray\Aop\MethodInvocation;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\AuraSqlModule\Exception\InvalidTransactionalPropertyException;
use Ray\AuraSqlModule\Exception\RollbackException;

final class PropTransaction
{
    public function __invoke(MethodInvocation $invocation, Transactional $transactional)
    {
        $object = $invocation->getThis();
        $transactions = [];
        foreach ($transactional->value as $prop) {
            $transactions[] = $this->begin($object, $prop);
        }
        try {
            $result = $invocation->proceed();
            foreach ($transactions as $db) {
                $db->commit();
            }
        } catch (\PDOException $e) {
            foreach ($transactions as $db) {
                $db->rollback();
            }
            throw new RollbackException($e->getMessage(), 0, $e);
        }

        return $result;
    }

    /**
     * @param object $object the object having pdo
     * @param string $prop   the name of pdo property
     *
     * @throws InvalidTransactionalPropertyException
     *
     * @return \Pdo
     */
    private function begin($object, $prop) : ExtendedPdoInterface
    {
        try {
            $ref = new \ReflectionProperty($object, $prop);
        } catch (\ReflectionException $e) {
            throw new InvalidTransactionalPropertyException($prop, 0, $e);
        }
        $ref->setAccessible(true);
        $db = $ref->getValue($object);
        $db->beginTransaction();

        return $db;
    }
}
