<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\DataDefinition;

use function sprintf;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@see https://www.edgedb.com/docs/edgeql/ddl/annotations}
 */
final class Annotation
{
    private const IDENTIFIER = 'ANNOTATION';

    /**
     * {@see https://www.edgedb.com/docs/edgeql/ddl/annotations#create-abstract-annotation}
     */
    public static function createAbstract(string $name, string $value): string
    {
        return sprintf("CREATE ABSTRACT %s %s := '%s';", self::IDENTIFIER, $name, $value);
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/ddl/annotations#alter-abstract-annotation}
     */
    public static function alterAbstract(string $name, string $value, ?string $subCommand = null): string
    {
        $alter = sprintf("ALTER ABSTRACT %s %s := '%s'", self::IDENTIFIER, $name, $value);

        if (null !== $subCommand && (0 === strpos($subCommand, 'RENAME TO'))) {
            return sprintf('%s { %s; };', $alter, $subCommand);
        }

        if (null !== $subCommand && (0 === strpos($subCommand, 'ALTER ANNOTATION'))) {
            return sprintf('%s { %s };', $alter, $subCommand);
        }

        if (null !== $subCommand && (0 === strpos($subCommand, 'DROP ANNOTATION'))) {
            return sprintf('%s { %s };', $alter, $subCommand);
        }

        return sprintf('%s;', $alter);
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/ddl/annotations#drop-abstract-annotation}
     */
    public static function dropAbstract(string $name): string
    {
        return sprintf('DROP ABSTRACT %s %s;', self::IDENTIFIER, $name);
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/ddl/annotations/#create-annotation}
     */
    public static function create(string $name, string $value): string
    {
        return sprintf("CREATE %s %s := '%s';", self::IDENTIFIER, $name, $value);
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/ddl/annotations/#alter-annotation}
     */
    public static function alter(string $name, string $value): string
    {
        return sprintf("ALTER %s %s := '%s';", self::IDENTIFIER, $name, $value);
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/ddl/annotations/#drop-annotation}
     */
    public static function drop(string $name): string
    {
        return sprintf('DROP %s %s;', self::IDENTIFIER, $name);
    }
}
