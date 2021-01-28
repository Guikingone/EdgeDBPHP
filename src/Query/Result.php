<?php

declare(strict_types=1);

namespace EdgeDB\Query;

use Closure;
use function array_filter;
use const ARRAY_FILTER_USE_BOTH;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class Result
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var array<string, string|int>|null
     */
    private $errors;

    /**
     * @param array                          $data
     * @param array<string, string|int>|null $errors
     */
    public function __construct(array $data, array $errors = null)
    {
        $this->data = $data;
        $this->errors = $errors;
    }

    public function filter(Closure $filter): self
    {
        return new self(array_filter($this->data, $filter, ARRAY_FILTER_USE_BOTH), $this->errors);
    }

    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return array<string, string|int>|null
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }
}
