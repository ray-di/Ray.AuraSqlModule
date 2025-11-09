<?php

declare(strict_types=1);

namespace Test;

use Aura\Sql\ExtendedPdoInterface;
use Ray\AuraSqlModule\Annotation\Read;

class UserFinder
{
    #[Read]
    public function __construct(
        private ExtendedPdoInterface $pdo
    ) {
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();

        return $result ?: null;
    }
}
