<?php

declare(strict_types=1);

namespace EdgeDB\Query;

use Closure;
use Countable;
use EdgeDB\Types\Set;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class HttpResult implements Countable
{
    private Set $set;

    /**
     * @var array<string, string|int>|null
     */
    private ?array $error;

    /**
     * @param array               $data
     * @param int[]|string[]|null $error
     */
    public function __construct(array $data, ?array $error)
    {
        $this->set = new Set($data);
        $this->error = $error;
    }

    public function filter(Closure $filter): Set
    {
        return $this->set->filter($filter);
    }

    public function getSet(): Set
    {
        return $this->set;
    }

    /**
     * @return array<string, string|int>|null
     */
    public function getError(): ?array
    {
        return $this->error;
    }

    public function toArray(): array
    {
        return [
            'data' => $this->set->getIterator()->getArrayCopy(),
            'errors' => $this->error,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return $this->set->count();
    }
}
