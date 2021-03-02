<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\Statement;

use EdgeDB\Exception\InvalidArgumentException;
use function in_array;
use function sprintf;
use function strpos;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class Transaction
{
    public const ISOLATION_REPEATABLE_READ = 'ISOLATION REPEATABLE READ';
    public const ISOLATION_SERIALIZABLE = 'ISOLATION SERIALIZABLE';
    public const READ_WRITE = 'READ WRITE';
    public const READ_ONLY = 'READ ONLY';

    public static function start(string $isolation = self::ISOLATION_REPEATABLE_READ, string $readMode = self::READ_WRITE, bool $deferrable = false): string
    {
        if (0 === strpos($isolation, 'ISOLATION') && !in_array($isolation, [self::ISOLATION_REPEATABLE_READ, self::ISOLATION_SERIALIZABLE], true)) {
            throw new InvalidArgumentException(sprintf('The isolation mode "%s" is not allowed', $isolation));
        }

        if (0 === strpos($readMode, 'READ') && !in_array($readMode, [self::READ_WRITE, self::READ_ONLY], true)) {
            throw new InvalidArgumentException(sprintf('The read mode "%s" is not allowed', $readMode));
        }

        if ($deferrable && ($isolation !== self::ISOLATION_SERIALIZABLE || $readMode !== self::READ_ONLY)) {
            throw new InvalidArgumentException('The transaction cannot be deferrable');
        }

        $start = sprintf('START TRANSACTION %s, %s', $isolation, $readMode);

        return $deferrable ? sprintf('%s, %s', $start, 'DEFERRABLE') : $start;
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/statements/tx_commit#commit}
     */
    public static function commit(): string
    {
        return 'COMMIT';
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/statements/tx_rollback#rollback}
     */
    public static function rollback(): string
    {
        return 'ROLLBACK';
    }
}
