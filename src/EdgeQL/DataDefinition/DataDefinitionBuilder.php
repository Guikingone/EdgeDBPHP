<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\DataDefinition;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class DataDefinitionBuilder
{
    private string $definition;

    public static function new(): self
    {
        return new self();
    }

    public function addIndex(string $expression, ?string $annotation = null): self
    {
        $this->definition .= Index::create($expression, $annotation);

        return $this;
    }

    public function getDefinition(): string
    {
        return $this->definition;
    }
}
