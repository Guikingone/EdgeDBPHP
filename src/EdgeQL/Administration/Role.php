<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\Administration;

use function sprintf;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@see https://www.edgedb.com/docs/edgeql/admin/roles}
 */
final class Role
{
    private const IDENTIFIER = 'ROLE';

    /**
     * {@see https://www.edgedb.com/docs/edgeql/admin/roles#create-role}
     */
    public static function create(string $role, bool $superUser = false, ?string $extending = null, ?string $password = null): string
    {
        $role = $superUser
            ? sprintf('CREATE SUPERUSER %s %s', self::IDENTIFIER, $role)
            : sprintf('CREATE %s %s', self::IDENTIFIER, $role)
        ;

        if (null !== $extending) {
            $role = sprintf('%s EXTENDING %s', $role, $extending);
        }

        if (null !== $password) {
            $role = sprintf('%s { SET password := %s }', $role, $password);
        }

        return $role;
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/admin/roles#alter-role}
     */
    public static function renameTo(string $role, string $renameTo): string
    {
        return sprintf('ALTER %s %s { RENAME TO %s; };', self::IDENTIFIER, $role, $renameTo);
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/admin/roles#alter-role}
     */
    public static function newPassword(string $role, string $password): string
    {
        return sprintf('ALTER %s %s { SET password := %s; };', self::IDENTIFIER, $role, $password);
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/admin/roles#drop-role}
     */
    public static function drop(string $role): string
    {
        return sprintf('DROP %s %s;', self::IDENTIFIER, $role);
    }
}
