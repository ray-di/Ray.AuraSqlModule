<?php

declare(strict_types=1);

namespace Test;

use Aura\SqlQuery\Common\SelectInterface;
use Ray\AuraSqlModule\Annotation\AuraSqlQueryConfig;

class UserQuery
{
    #[AuraSqlQueryConfig]
    public function __construct(
        private SelectInterface $select
    ) {
    }

    public function getActiveUsers(): SelectInterface
    {
        return $this->select
            ->cols(['*'])
            ->from('users')
            ->where('active = 1');
    }
}
