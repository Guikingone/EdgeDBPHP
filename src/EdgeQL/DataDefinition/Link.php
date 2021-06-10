<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\DataDefinition;

use function sprintf;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@see https://www.edgedb.com/docs/edgeql/ddl/links}
 */
final class Link
{
    private const IDENTIFIER = 'LINK';

    /**
     * {@internal https://www.edgedb.com/docs/edgeql/ddl/links/#drop-link}
     */
    public static function drop(string $name, bool $abstract = false): string
    {
        return sprintf('%s %s %s;', $abstract ? 'DROP ABSTRACT' : 'DROP', self::IDENTIFIER, $name);
    }
}
