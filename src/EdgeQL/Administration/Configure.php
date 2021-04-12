<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\Administration;

use EdgeDB\Exception\InvalidArgumentException;
use function array_map;
use function explode;
use function implode;
use function in_array;
use function sprintf;
use function strpos;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@see https://www.edgedb.com/docs/edgeql/admin/configure}
 */
final class Configure
{
    private const IDENTIFIER = 'CONFIGURE';
    private const ALLOWED_CONFIGURATION_KEYS = [
        'SESSION',
        'CURRENT DATABASE',
        'SYSTEM',
    ];

    /**
     * {@internal https://www.edgedb.com/docs/edgeql/admin/configure/}
     */
    public static function new(string $scope, string $parameter, string $value): string
    {
        if (!in_array($scope, self::ALLOWED_CONFIGURATION_KEYS, true)) {
            throw new InvalidArgumentException(sprintf('The scope "%s" is not allowed', $scope));
        }

        if (false !== strpos($value, ', ')) {
            return sprintf("%s %s SET %s := {%s};", self::IDENTIFIER, $scope, $parameter, implode(', ', array_map(function (string $value): string {
                return sprintf("'%s'", $value);
            }, explode(', ', $value))));
        }

        return sprintf("%s %s SET %s := '%s';", self::IDENTIFIER, $scope, $parameter, $value);
    }

    /**
     * {@internal https://www.edgedb.com/docs/edgeql/admin/configure/}
     */
    public static function reset(string $scope, string $parameter, ?string $filter = null): string
    {
        if (!in_array($scope, self::ALLOWED_CONFIGURATION_KEYS, true)) {
            throw new InvalidArgumentException(sprintf('The scope %s is not allowed', $scope));
        }

        return null !== $filter
            ? sprintf("%s %s RESET %s FILTER %s;", self::IDENTIFIER, $scope, $parameter, $filter)
            : sprintf("%s %s RESET %s;", self::IDENTIFIER, $scope, $parameter)
        ;
    }
}
