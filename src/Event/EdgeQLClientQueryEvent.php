<?php

declare(strict_types=1);

namespace EdgeDB\Event;

use Psr\EventDispatcher\StoppableEventInterface;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class EdgeQLClientQueryEvent implements StoppableEventInterface
{
    private string $method;
    private string $query;
    private ?array $variables;

    /**
     * @param array|null $variables
     */
    public function __construct(
        string $method,
        string $query,
        ?array $variables = null
    ) {
        $this->method = $method;
        $this->query = $query;
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

    /**
     * @return array|null
     */
    public function getVariables(): ?array
    {
        return $this->variables;
    }

    public function isPropagationStopped(): bool
    {
        return false;
    }
}
