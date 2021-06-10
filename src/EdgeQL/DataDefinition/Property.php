<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\DataDefinition;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@see https://www.edgedb.com/docs/edgeql/ddl/props}
 */
final class Property
{
    private const IDENTIFIER = 'PROPERTY';

    /**
     * {@internal https://www.edgedb.com/docs/edgeql/ddl/props/#drop-property}
     */
    public static function drop(string $property, bool $abstract = false): string
    {
        return $abstract
            ? sprintf('DROP ABSTRACT %s %s;', self::IDENTIFIER, $property)
            : sprintf('DROP %s %s;', self::IDENTIFIER, $property)
        ;
    }
}
