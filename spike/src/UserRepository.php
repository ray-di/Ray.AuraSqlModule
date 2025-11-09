<?php

declare(strict_types=1);

namespace Test;

use Ray\AuraSqlModule\Annotation\Transactional;

class UserRepository
{
    #[Transactional]
    public function save(array $user): void
    {
        // Save user to database
    }

    #[Transactional(['pdo1', 'pdo2'])]
    public function saveMulti(array $user): void
    {
        // Save to multiple databases
    }
}
