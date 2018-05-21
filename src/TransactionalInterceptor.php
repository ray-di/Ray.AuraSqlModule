<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdoInterface;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;
use Ray\Aop\ReflectionMethod;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\AuraSqlModule\Exception\RollbackException;

class TransactionalInterceptor implements MethodInterceptor
{
    /**
     * @var ExtendedPdoInterface | null
     */
    private $pdo;

    public function __construct(ExtendedPdoInterface $pdo = null)
    {
        $this->pdo = $pdo;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(MethodInvocation $invocation)
    {
        /** @var ReflectionMethod $method */
        $method = $invocation->getMethod();
        /** @var Transactional $transactional */
        $transactional = $method->getAnnotation(Transactional::class);
        if (\count($transactional->value) > 1) {
            return (new PropTransaction)($invocation, $transactional);
        }
        if (! $this->pdo instanceof ExtendedPdoInterface) {
            return $invocation->proceed();
        }
        try {
            $this->pdo->beginTransaction();
            $result = $invocation->proceed();
            $this->pdo->commit();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            throw new RollbackException($e->getMessage(), 0, $e);
        }

        return $result;
    }
}
