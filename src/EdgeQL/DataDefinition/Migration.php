<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\DataDefinition;

use EdgeDB\Exception\InvalidArgumentException;
use function in_array;
use function sprintf;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@see https://www.edgedb.com/docs/edgeql/ddl/migrations}
 */
final class Migration
{
    private const IDENTIFIER = 'MIGRATION';

    public static function start(string $statement): string
    {
        // TODO

        return sprintf('START %s TO { %s }', self::IDENTIFIER, $statement);
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/ddl/migrations#create-migration}
     */
    public static function create(string $statement): string
    {
        // TODO

        return sprintf('CREATE %s { %s }', self::IDENTIFIER, $statement);
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/ddl/migrations#abort-migration}
     */
    public static function abort(): string
    {
        return sprintf('ABORT %s', self::IDENTIFIER);
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/ddl/migrations#populate-migration}
     */
    public static function populate(): string
    {
        return sprintf('POPULATE %s', self::IDENTIFIER);
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/ddl/migrations#describe-current-migration}
     */
    public static function describeCurrent(string $as): string
    {
        if (!in_array($as, ['DDL', 'JSON'], true)) {
            throw new InvalidArgumentException(sprintf('The format "%s" is not supported', $as));
        }

        return sprintf('DESCRIBE CURRENT %s AS %s', self::IDENTIFIER, $as);
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/ddl/migrations#commit-migration}
     */
    public static function commit(): string
    {
        return sprintf('COMMIT %s', self::IDENTIFIER);
    }
}
