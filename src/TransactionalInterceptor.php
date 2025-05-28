<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdoInterface;
use Override;
use PDOException;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\AuraSqlModule\Exception\RollbackException;

use function count;

final class TransactionalInterceptor implements MethodInterceptor
{
    public function __construct(private readonly ?ExtendedPdoInterface $pdo = null)
    {
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function invoke(MethodInvocation $invocation)
    {
        $method = $invocation->getMethod();
        $transactional = $method->getAnnotation(Transactional::class);
        if ($transactional instanceof Transactional && count((array) $transactional->value) > 1) {
            return (new PropTransaction())($invocation, $transactional);
        }

        if (! $this->pdo instanceof ExtendedPdoInterface) {
            return $invocation->proceed();
        }

        try {
            $this->pdo->beginTransaction();
            $result = $invocation->proceed();
            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();

            throw new RollbackException($e->getMessage(), 0, $e);
        }

        return $result;
    }
}
