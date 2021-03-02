<?php

declare(strict_types=1);

namespace EdgeDB\Types;

use ArrayIterator;
use Closure;
use Countable;
use IteratorAggregate;
use function count;
use const ARRAY_FILTER_USE_BOTH;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class Set implements Countable, IteratorAggregate
{
    private array $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    public function filter(Closure $filter): self
    {
        return new self(array_filter($this->fields, $filter, ARRAY_FILTER_USE_BOTH));
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->fields);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return count($this->fields);
    }
}
