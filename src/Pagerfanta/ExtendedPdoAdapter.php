<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use Override;
use Pagerfanta\Adapter\AdapterInterface;
use Ray\AuraSqlModule\Exception\AdapterDetached;

use function assert;
use function count;
use function is_array;
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
final class ExtendedPdoAdapter implements AdapterInterface
{
    private readonly FetcherInterface $fetcher;

    /** Null once restored by unserialize() */
    private readonly ExtendedPdoInterface|null $pdo;

    /**
     * @param array<mixed> $params
     *
     * @psalm-taint-sink sql $sql
     */
    public function __construct(
        ExtendedPdoInterface $pdo,
        private readonly string $sql,
        private readonly array $params,
        ?FetcherInterface $fetcher = null
    ) {
        $this->pdo = $pdo;
        $this->fetcher = $fetcher ?? new FetchAssoc($pdo);
    }

    /**
     * Nothing is kept: a restored adapter cannot query, and Pagerfanta answers from the results it
     * already fetched.
     *
     * @return array<never>
     */
    public function __serialize(): array
    {
        return [];
    }

    /** @param array<never> $data */
    public function __unserialize(array $data): void
    {
        assert($data === []);
        $this->pdo = null;
    }

    /**
     * {@inheritDoc}
     *
     * @SuppressWarnings(PHPMD.GotoStatement) // @phpstan-ignore-line
     */
    #[Override]
    public function getNbResults(): int
    {
        $pdo = $this->pdo();
        // be smart and try to guess the total number of records
        $countQuery = $this->rewriteCountQuery($this->sql);
        if (! $countQuery) {
            // GROUP BY => fetch the whole result set and count the rows returned
            $result = $pdo->perform($this->sql, $this->params)->fetchAll();
            $count = ! $result ? 0 : count($result);
            goto ret;
        }

        if ($this->params) {
            $count = $pdo->fetchValue($countQuery, $this->params);
            goto ret;
        }

        $count = $pdo->fetchValue($countQuery);
        ret:
        /** @var string $count */
        $nbResult = ! $count ? 0 : (int) $count;
        assert($nbResult >= 0);

        return $nbResult;
    }

    /**
     * {@inheritDoc}
     *
     * @return array<mixed>
     */
    #[Override]
    public function getSlice(int $offset, int $length): iterable
    {
        $this->pdo();
        $sql = $this->sql . $this->getLimitClause($offset, $length);
        $result = ($this->fetcher)($sql, $this->params);

        return ! $result ? [] : $result;
    }

    /**
     * {@inheritDoc}
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
        $split = preg_split('/\s+ORDER\s+BY\s+/is', (string) $queryCount);
        assert(is_array($split), 'preg_split() should return an array');
        [$queryCount] = $split;
        $split2 = preg_split('/\bLIMIT\b/is', (string) $queryCount);
        assert(is_array($split2), 'preg_split() should return an array');
        [$queryCount2] = $split2;

        return trim((string) $queryCount2);
    }

    private function pdo(): ExtendedPdoInterface
    {
        if ($this->pdo === null) {
            throw new AdapterDetached();
        }

        return $this->pdo;
    }
}
