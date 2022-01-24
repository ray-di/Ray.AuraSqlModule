<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdo;
use Aura\SqlQuery\Common\SelectInterface;
use Aura\SqlQuery\QueryFactory;
use PDO;
use PHPUnit\Framework\TestCase;

use function class_exists;

abstract class AuraSqlQueryTestCase extends TestCase
{
    /** @var ExtendedPdo */
    protected $pdo;

    /** @var SelectInterface */
    protected $select;

    /** @var QueryFactory */
    protected $qf;

    protected function setUp(): void
    {
        if ($this->isAuraSqlQueryNotAvailable()) {
            $this->markTestSkipped('Aura Sql Query is not available');
        }

        $this->qf = new QueryFactory('sqlite');
        $this->select = $this->qf->newSelect();

        $this->pdo = $this->getConnection();
        $this->createSchema($this->pdo);
        $this->insertData($this->pdo);
        $this->select->cols(['p.*'])->from('posts as p');
    }

    private function isAuraSqlQueryNotAvailable(): bool
    {
        return ! class_exists(\Aura\SqlQuery\QueryFactory::class);
    }

    private function getConnection(): ExtendedPdo
    {
        return new ExtendedPdo('sqlite::memory:', '', '', [PDO::ATTR_STRINGIFY_FETCHES => true]);
    }

    private function createSchema(ExtendedPdo $pdo): void
    {
        $stm = 'CREATE TABLE IF NOT EXISTS `posts` (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username VARCHAR(32) NOT NULL,
            post_content varchar(128)
        )';
        $pdo->exec($stm);
        $stm = 'CREATE TABLE IF NOT EXISTS `comments` (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            post_id INTEGER,
            username VARCHAR(32),
            content TEXT
        )';
        $pdo->exec($stm);
    }

    private function insertData(ExtendedPdo $pdo): void
    {
        $insertPost = $this->qf->newInsert();
        $insertComment = $this->qf->newInsert();
        $insertComment->into('comments');

        for ($i = 1; $i <= 50; $i++) {
            $insertPost
                ->into('posts')
                ->cols([
                    'username' => 'Jon Doe',
                    'post_content' => 'Post #' . $i,
                ]);
            $sth = $pdo->prepare($insertPost->getStatement());
            $sth->execute($insertPost->getBindValues());
            for ($j = 1; $j <= 5; $j++) {
                $insertComment
                    ->into('comments')
                    ->cols([
                        'post_id' => $i,
                        'username' => 'Jon Doe',
                        'content' => 'Comment #' . $j,
                    ]);
                $sth = $pdo->prepare($insertComment->getStatement());
                $sth->execute($insertComment->getBindValues());
            }
        }
    }
}
