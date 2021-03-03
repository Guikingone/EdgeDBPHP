<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\SchemaDefinition;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class SchemaDefinitionBuilder
{
    private string $definition;

    public function getDefinition(): string
    {
        return $this->definition;
    }
}
