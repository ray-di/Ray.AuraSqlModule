<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use Pagerfanta\Adapter\AdapterInterface;
use PDO;

use function assert;
use function count;
use function is_int;
use function preg_match;
use function preg_replace;
use function preg_split;
use function strpos;
use function strtolower;
use function trim;

use const PHP_EOL;

/**
 * @template T
 * @implements AdapterInterface<T>
 */
class ExtendedPdoAdapter implements AdapterInterface
{
    private ExtendedPdoInterface $pdo;
    private string $sql;

    /** @var array<mixed> */
    private array $params;

    /**
     * @param array<mixed> $params
     */
    public function __construct(ExtendedPdoInterface $pdo, string $sql, array $params)
    {
        $this->pdo = $pdo;
        $this->sql = $sql;
        $this->params = $params;
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.GotoStatement)
     */
    public function getNbResults(): int
    {
        // be smart and try to guess the total number of records
        $countQuery = $this->rewriteCountQuery($this->sql);
        if (! $countQuery) {
            // GROUP BY => fetch the whole result set and count the rows returned
            $result = $this->pdo->perform($this->sql, $this->params)->fetchAll();
            $count = ! $result ? 0 : count($result);
            goto ret;
        }

        if ($this->params) {
            $count = $this->pdo->fetchValue($countQuery, $this->params);
            goto ret;
        }

        $count = $this->pdo->fetchValue($countQuery);
        ret:
        /** @var string $count */
        $nbResult = ! $count ? 0 : (int) $count;
        assert(is_int($nbResult));
        assert($nbResult >= 0);

        return $nbResult;
    }

    /**
     * {@inheritdoc}
     *
     * @param int $offset
     * @param int $length
     *
     * @return array<array<mixed>>
     */
    public function getSlice(int $offset, int $length): iterable
    {
        $sql = $this->sql . $this->getLimitClause($offset, $length);
        $result = $this->pdo->perform($sql, $this->params)->fetchAll(PDO::FETCH_ASSOC);

        return ! $result ? [] : $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getLimitClause(int $offset, int $length): string
    {
        if ($offset && $length) {
            return PHP_EOL . "LIMIT {$length} OFFSET {$offset}";
        }

        if ($length) {
            return PHP_EOL . "LIMIT {$length}";
        }

        return '';
    }

    /**
     * Return count query
     *
     * @param string $query
     *
     * @return string
     *
     * @see https://github.com/pear/Pager/blob/master/examples/Pager_Wrapper.php
     * Taken from pear/pager and modified.
     * tested at https://github.com/pear/Pager/blob/80c0e31c8b94f913cfbdeccbe83b63822f42a2f8/tests/pager_wrapper_test.php#L19
     * @codeCoverageIgnore
     */
    public function rewriteCountQuery($query)
    {
        if (is_int(strpos(strtolower($query), 'union'))) {
            return '';
        }

        if (preg_match('/^\s*SELECT\s+\bDISTINCT\b/is', $query) || preg_match('/\s+GROUP\s+BY\s+/is', $query)) {
            return '';
        }

        $openParenthesis = '(?:\()';
        $closeParenthesis = '(?:\))';
        $subQueryInSelect = $openParenthesis . '.*\bFROM\b.*' . $closeParenthesis;
        $pattern = '/(?:.*' . $subQueryInSelect . '.*)\bFROM\b\s+/Uims';
        if (preg_match($pattern, $query)) {
            return '';
        }

        $subQueryWithLimitOrder = $openParenthesis . '.*\b(LIMIT|ORDER)\b.*' . $closeParenthesis;
        $pattern = '/.*\bFROM\b.*(?:.*' . $subQueryWithLimitOrder . '.*).*/Uims';
        if (preg_match($pattern, $query)) {
            return '';
        }

        $queryCount = preg_replace('/(?:.*)\bFROM\b\s+/Uims', 'SELECT COUNT(*) FROM ', $query, 1);
        /** @var array<int> $split */
        $split = preg_split('/\s+ORDER\s+BY\s+/is', (string) $queryCount);
        [$queryCount] = $split;
        /** @var array<int> $split2 */
        $split2 = preg_split('/\bLIMIT\b/is', (string) $queryCount);
        [$queryCount2] = $split2;

        return trim((string) $queryCount2);
    }
}
