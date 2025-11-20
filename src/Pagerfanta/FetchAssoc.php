<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use Override;
use PDO;

final class FetchAssoc implements FetcherInterface
{
    public function __construct(private ExtendedPdoInterface $pdo)
    {
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function __invoke(string $sql, array $params): array
    {
        return $this->pdo->perform($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
}
