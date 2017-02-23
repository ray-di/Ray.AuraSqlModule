<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\AuraSqlModule\Exception\InvalidTransactionalPropertyException;
use Ray\AuraSqlModule\Exception\RollbackException;

class TransactionalInterceptor implements MethodInterceptor
{
    /**
     * {@inheritdoc}
     */
    public function invoke(MethodInvocation $invocation)
    {
        $transactional = $invocation->getMethod()->getAnnotation(Transactional::class);
        $object = $invocation->getThis();
        $transactions = [];
        /* @var $transactions \PDO[] */
        foreach ($transactional->value as $prop) {
            $transactions[] = $this->beginTransaction($object, $prop);
        }
        try {
            $result = $invocation->proceed();
            foreach ($transactions as $db) {
                $db->commit();
            }
        } catch (\Exception $e) {
            foreach ($transactions as $db) {
                $db->rollback();
            }
            throw new RollbackException($e, 0, $e);
        }

        return $result;
    }

    /**
     * @param object $object the object having pdo
     * @param string $prop   the name of pdo property
     *
     * @return \Pdo
     * @throws InvalidTransactionalPropertyException
     */
    private function beginTransaction($object, $prop)
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
