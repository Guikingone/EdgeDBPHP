<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\DataDefinition;

use EdgeDB\EdgeQL\Statement\With;
use function sprintf;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@see https://www.edgedb.com/docs/edgeql/ddl/objects}
 */
final class ObjectType
{
    private const IDENTIFIER = 'TYPE';

    /**
     * {@see https://www.edgedb.com/docs/edgeql/ddl/objects/#create-type}
     */
    public static function create(string $name, ?string $with = null, ?bool $abstract = false, ?string $extending = null, ?string $subCommand = null): string
    {
        $create = sprintf('CREATE %s TYPE %s', null !== $abstract && $abstract ? 'ABSTRACT' : '', $name);

        if (null !== $with) {
            $create = sprintf('WITH %s %s', With::new($with), $create);
        }

        return $create;
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/ddl/objects/#alter-type}
     */
    public static function alter(string $name, ?string $subCommand = null): string
    {
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/ddl/objects#drop-type}
     */
    public static function drop(string $type): string
    {
        return sprintf('DROP %s %s', self::IDENTIFIER, $type);
    }
}
