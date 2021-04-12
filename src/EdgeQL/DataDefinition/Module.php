<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\DataDefinition;

use function sprintf;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@see https://www.edgedb.com/docs/edgeql/ddl/modules}
 */
final class Module
{
    private const IDENTIFIER = 'MODULE';

    /**
     * {@see https://www.edgedb.com/docs/edgeql/ddl/modules/#create-module}
     */
    public static function create(string $name, bool $ifNotExists = false): string
    {
        return $ifNotExists
            ? sprintf('CREATE %s %s IF NOT EXISTS;', self::IDENTIFIER, $name)
            : sprintf('CREATE %s %s;', self::IDENTIFIER, $name)
        ;
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/ddl/modules/#drop-module}
     */
    public static function drop(string $name): string
    {
        return sprintf('DROP %s %s;', self::IDENTIFIER, $name);
    }
}
