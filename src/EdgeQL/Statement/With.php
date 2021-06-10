<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\Statement;

use function sprintf;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@see https://www.edgedb.com/docs/edgeql/statements/with}
 */
final class With
{
    /**
     * {@see https://www.edgedb.com/docs/edgeql/statements/with#specifying-a-module}
     */
    public static function new(string $name, ?string $alias = null): string
    {
        return null === $alias
            ? sprintf('WITH MODULE %s', $name)
            : sprintf('WITH MODULE %s AS MODULE %s', $name, $alias)
        ;
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/statements/with#local-expression-aliases}
     */
    public static function newWithQueryAlias(string $name, ?string $alias = null): string
    {
        return null === $alias
            ? sprintf('WITH MODULE %s', $name)
            : sprintf('WITH MODULE %s, %s', $name, $alias)
        ;
    }
}
