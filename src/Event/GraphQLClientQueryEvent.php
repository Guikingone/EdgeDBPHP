<?php

declare(strict_types=1);

namespace EdgeDB\Event;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class GraphQLClientQueryEvent
{
    private string $method;
    private string $query;
    private ?string $operationName;
    private ?array $variables;

    /**
     * @param array $variables
     */
    public function __construct(
        string $method,
        string $query,
        ?string $operationName = null,
        ?array $variables = null
    ) {
        $this->method = $method;
        $this->query = $query;
        $this->operationName = $operationName;
        $this->variables = $variables;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getOperationName(): ?string
    {
        return $this->operationName;
    }

    /**
     * @return array
     */
    public function getVariables(): array
    {
        return $this->variables;
    }
}
