<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\DataDefinition;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@see https://www.edgedb.com/docs/edgeql/ddl/aliases}
 */
final class Alias
{
    private const IDENTIFIER = 'ALIAS';

    /**
     * {@see https://www.edgedb.com/docs/edgeql/ddl/aliases#drop-alias}
     */
    public static function drop(string $alias): string
    {
        return sprintf('DROP %s %s', self::IDENTIFIER, $alias);
    }
}