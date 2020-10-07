<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use const PHP_EOL;

class ExtendedPdoAdapterTest extends AbstractPdoTestCase
{
    /** @var ExtendedPdoAdapter */
    protected $pdoAdapter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pdoAdapter = new ExtendedPdoAdapter($this->pdo, 'SELECT * FROM posts', []);
    }

    public function testGetNbResults()
    {
        $nbResult = $this->pdoAdapter->getNbResults();
        $this->assertSame(50, $nbResult);
    }

    public function testGetLimitClause()
    {
        $limitClause = $this->pdoAdapter->getLimitClause(1, 10);
        $this->assertSame(PHP_EOL . 'LIMIT 10 OFFSET 1', $limitClause);
    }

    public function testGetLimitClauseZeroOffset()
    {
        $limitClause = $this->pdoAdapter->getLimitClause(0, 10);
        $this->assertSame(PHP_EOL . 'LIMIT 10', $limitClause);
    }

    public function testGetLimitClauseZeroOffsetZeroLimit()
    {
        $limitClause = $this->pdoAdapter->getLimitClause(0, 0);
        $this->assertSame('', $limitClause);
    }

    public function testGetSlice()
    {
        $slice = $this->pdoAdapter->getSlice(2, 1);
        $expected = [
            [
                'id' => '3',
                'username' => 'BEAR',
                'post_content' => 'entry #3',
            ],
        ];
        $this->assertSame($expected, $slice);
    }

    /**
     * @return array<array<string>>
     */
    public function splProvider(): array
    {
        return [
            ['SELECT * FROM posts', [], 'SELECT COUNT(*) FROM posts', 50],
            ['SELECT DISTINCT id FROM posts', [], '', 50],
            ['SELECT DISTINCT id FROM posts WHERE id > :num', ['num' => 10], '', 40],
            ['SELECT * FROM posts WHERE id > :num', ['num' => 10], 'SELECT COUNT(*) FROM posts WHERE id > :num', 40],
            ['SELECT id FROM posts UNION SELECT id FROM posts', [], '', 50],
        ];
    }

    /**
     * @phpstan-param array<mixed> $params
     *
     * @dataProvider splProvider
     */
    public function testRewriteCountQuery(string $sql, array $params, string $expectedCountQuery, int $expectedNbResult): void
    {
        $pdoAdapter = new ExtendedPdoAdapter($this->pdo, $sql, $params);
        $rewrite = $pdoAdapter->rewriteCountQuery($sql);
        $this->assertSame($expectedCountQuery, $rewrite);
        $nbResult = $pdoAdapter->getNbResults();
        $this->assertSame($expectedNbResult, $nbResult);
    }
}
