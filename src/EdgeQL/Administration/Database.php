<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\Administration;

use function sprintf;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@see https://www.edgedb.com/docs/edgeql/admin/databases}
 */
final class Database
{
    private const IDENTIFIER = 'DATABASE';

    /**
     * {@see https://www.edgedb.com/docs/edgeql/admin/databases#create-database}
     */
    public static function create(string $name): string
    {
        return sprintf('CREATE %s %s', self::IDENTIFIER, $name);
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/admin/databases#drop-database}
     */
    public static function drop(string $name): string
    {
        return sprintf('DROP %s %s', self::IDENTIFIER, $name);
    }
}
