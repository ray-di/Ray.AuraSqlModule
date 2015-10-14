<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;
use Ray\AuraSqlModule\Exception\RollbackException;

class TransactionalInterceptor implements MethodInterceptor
{
    const PROP = 'pdo';

    /**
     * {@inheritdoc}
     */
    public function invoke(MethodInvocation $invocation)
    {
        $object = $invocation->getThis();
        $ref = new \ReflectionProperty($object, self::PROP);
        $ref->setAccessible(true);
        $db = $ref->getValue($object);
        $db->beginTransaction();
        try {
            $result = $invocation->proceed();
            $db->commit();
        } catch (\Exception $e) {
            $db->rollback();
            throw new RollbackException($e, 0, $e);
        }

        return $result;
    }
}
