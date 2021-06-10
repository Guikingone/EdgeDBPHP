<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\Statement;

use EdgeDB\EdgeQL\DataDefinition\Module;
use EdgeDB\Exception\RuntimeException;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class StatementBuilder
{
    private array $parts;

    private function __construct()
    {
        $this->parts = [];
    }

    public static function new(): self
    {
        return new self();
    }

    public function with(string $module, bool $ifNotExists = false): self
    {
        if (null !== $this->parts['module']) {
            throw new RuntimeException('The module alias must be the root statement');
        }

        $this->parts['module'] = Module::create($module, $ifNotExists);

        return $this;
    }

    public function delete(string $expression, ?string $filter = null): self
    {
        $this->parts['delete'][] = Delete::delete($expression, $filter);

        return $this;
    }
}
