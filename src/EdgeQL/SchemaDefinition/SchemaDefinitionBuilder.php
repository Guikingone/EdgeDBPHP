<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\SchemaDefinition;

use function strpos;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class SchemaDefinitionBuilder
{
    private string $definition;

    public function __construct()
    {
        $this->definition = '';
    }

    public function newModule(string $module): self
    {
        $this->definition .= $module;

        return $this;
    }

    public function addNewModule(string $module): self
    {
        if (false === strpos($this->definition, 'module')) {
            return $this->newModule($module);
        }

        $this->definition .= ' ' . $module;

        return $this;
    }

    public function getDefinition(): string
    {
        return $this->definition;
    }
}
