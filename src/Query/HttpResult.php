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
    /**
     * @var Set<string, mixed>
     */
    private Set $set;

    /**
     * @var array<string, string|int>|null
     */
    private ?array $error;

    /**
     * @param array<string, mixed> $data
     * @param int[]|string[]|null  $error
     */
    public function __construct(
        array $data,
        ?array $error =  null
    ) {
        $this->set = new Set($data);
        $this->error = $error;
    }

    /**
     * @param Closure $filter
     *
     * @return Set<string, mixed>
     */
    public function filter(Closure $filter): Set
    {
        return $this->set->filter($filter);
    }

    /**
     * @return Set<string, mixed>
     */
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
