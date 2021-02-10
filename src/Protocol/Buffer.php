<?php

declare(strict_types=1);

namespace EdgeDB\Protocol;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class Buffer
{
    /**
     * @var array<int, mixed>
     */
    private array $buffer;

    public function __construct()
    {
        $this->buffer = [];
    }

    public function newLine(): void
    {
        $this->buffer[] = '';
    }

    /**
     * @param mixed $value
     */
    public function add($value): void
    {
        $this->buffer[] = $value;
    }

    public function render(): string
    {
        return implode('\n', $this->buffer);
    }
}
