<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use PDO;

final class FetchAssoc implements FetcherInterface
{
    /** @var ExtendedPdoInterface */
    private $pdo;

    public function __construct(ExtendedPdoInterface $pdo)
    {
        $this->pdo = $pdo;
    }

    public function __invoke(string $sql, array $params): array
    {
        return $this->pdo->perform($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
}
