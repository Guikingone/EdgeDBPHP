<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\Statement;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@see https://www.edgedb.com/docs/edgeql/statements/tx_sp_declare}
 */
final class Savepoint
{
    private const IDENTIFIER = 'SAVEPOINT';

    /**
     * {@see https://www.edgedb.com/docs/edgeql/statements/tx_sp_declare#declare-savepoint}
     */
    public static function create(string $name): string
    {
        return sprintf('DECLARE %s %s', self::IDENTIFIER, $name);
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/statements/tx_sp_release#release-savepoint}
     */
    public static function release(string $name): string
    {
        return sprintf('RELEASE %s %s', self::IDENTIFIER, $name);
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/statements/tx_sp_rollback#rollback-to-savepoint}
     */
    public static function rollback(string $name): string
    {
        return sprintf('ROLLBACK TO %s %s', self::IDENTIFIER, $name);
    }
}
