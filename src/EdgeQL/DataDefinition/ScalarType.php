<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\DataDefinition;

use function sprintf;
use function strpos;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@see https://www.edgedb.com/docs/edgeql/ddl/scalars}
 */
final class ScalarType
{
    private const IDENTIFIER = 'SCALAR';

    /**
     * {@see https://www.edgedb.com/docs/edgeql/ddl/scalars#create-scalar-type}
     */
    public static function create(string $name, ?string $with = null, bool $abstract = false, ?string $extending = null, ?string $subCommand = null): string
    {
        $create = sprintf('%s %s TYPE %s', $abstract ? 'CREATE ABSTRACT' : 'CREATE', self::IDENTIFIER, $name);

        if (null !== $with) {
            $create = sprintf('WITH %s %s', $with, $create);
        }

        if (null !== $extending) {
            $create = sprintf('%s EXTENDING %s', $create, $extending);
        }

        if (null !== $subCommand && 0 === strpos($subCommand, 'CREATE ANNOTATION')) {
            $create = sprintf('%s { %s }', $create, $subCommand);
        }

        if (null !== $subCommand && 0 === strpos($subCommand, 'CREATE CONSTRAINT')) {
            $create = sprintf('%s { %s }', $create, $subCommand);
        }

        return $create;
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/ddl/scalars#drop-scalar-type}
     */
    public static function drop(string $name, ?string $with = null): string
    {
        $drop = sprintf('DROP %s TYPE %s', self::IDENTIFIER, $name);

        if (null !== $with) {
            $drop = sprintf('WITH %s %s', $with, $drop);
        }

        return $drop;
    }
}
