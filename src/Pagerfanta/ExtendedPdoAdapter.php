<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use Pagerfanta\Adapter\AdapterInterface;
use PDO;

use function count;
use function is_int;
use function preg_match;
use function preg_replace;
use function preg_split;
use function strpos;
use function strtolower;
use function trim;

use const PHP_EOL;

class ExtendedPdoAdapter implements AdapterInterface
{
    /** @var ExtendedPdoInterface */
    private $pdo;

    /** @var string */
    private $sql;

    /** @var array<mixed> */
    private $params;

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
     */
    public function getNbResults()
    {
        // be smart and try to guess the total number of records
        $countQuery = $this->rewriteCountQuery($this->sql);
        if (! $countQuery) {
            // GROUP BY => fetch the whole result set and count the rows returned
            $result = $this->pdo->perform($this->sql, $this->params)->fetchAll();

            return ! $result ? 0 : count($result);
        }

        if ($this->params) {
            $count = $this->pdo->fetchValue($countQuery, $this->params);

            return ! $count ? 0 : (int) $count;
        }

        $count = $this->pdo->fetchValue($countQuery);

        return ! $count ? 0 : (int) $count;
    }

    /**
     * {@inheritdoc}
     *
     * @return array<array>
     */
    public function getSlice($offset, $length)
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
        [$queryCount] = preg_split('/\s+ORDER\s+BY\s+/is', (string) $queryCount);
        [$queryCount] = preg_split('/\bLIMIT\b/is', (string) $queryCount);

        return trim((string) $queryCount);
    }
}
