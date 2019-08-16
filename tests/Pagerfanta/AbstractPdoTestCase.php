<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdo;
use PHPUnit\Framework\TestCase;

abstract class AbstractPdoTestCase extends TestCase
{
    /**
     * @var ExtendedPdo
     */
    protected $pdo;

    protected function setUp() : void
    {
        $this->pdo = $this->getConnection();
        $this->createSchema($this->pdo);
        $this->insertData($this->pdo);
    }

    private function getConnection()
    {
        return new ExtendedPdo('sqlite::memory:');
    }

    private function createSchema(\PDO $pdo)
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `posts` (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username VARCHAR(32) NOT NULL,
            post_content varchar(128)
        )';
        $pdo->exec($sql);
    }

    private function insertData(\PDO $pdo)
    {
        unset($pdo);
        $sql = '
            INSERT INTO posts (
                username,
                post_content
            ) VALUES(
                :username,
                :post_content
        )';
        $sth = $this->pdo->prepare($sql);
        for ($i = 1; $i <= 50; $i++) {
            $userName = 'BEAR';
            $content = 'entry #' . $i;
            $sth->bindParam(':username', $userName, \PDO::PARAM_STR);
            $sth->bindParam(':post_content', $content, \PDO::PARAM_STR);
            $sth->execute();
        }
        $result = $this->pdo->query('SELECT * FROM posts')->fetchColumn();
    }
}
