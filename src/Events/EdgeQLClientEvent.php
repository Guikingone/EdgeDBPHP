<?php

declare(strict_types=1);

namespace EdgeDB\Events;

use Psr\EventDispatcher\StoppableEventInterface;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class EdgeQLClientEvent implements StoppableEventInterface
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $query;

    /**
     * @var array
     */
    private $variables;

    /**
     * @param string $method
     * @param string $query
     * @param array $variables
     */
    public function __construct(string $method, string $query, array $variables = [])
    {
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
     * @return array
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    public function isPropagationStopped(): bool
    {
        return false;
    }
}