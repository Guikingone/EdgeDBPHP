<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\Statement;

use function sprintf;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@see https://www.edgedb.com/docs/edgeql/statements/sess_reset_alias}
 */
final class Reset
{
    private const IDENTIFIER = 'RESET';

    public static function resetModule(string $module): string
    {
        return sprintf('%s MODULE %s', self::IDENTIFIER, $module);
    }

    public static function resetAlias(string $name): string
    {
        return sprintf('%s ALIAS %s', self::IDENTIFIER, $name);
    }

    public static function resetAllAlias(): string
    {
        return sprintf('%s ALIAS *', self::IDENTIFIER);
    }
}
