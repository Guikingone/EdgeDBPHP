<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\SchemaDefinition;

use function count;
use function sprintf;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@internal https://www.edgedb.com/docs/edgeql/sdl/objects}
 */
final class ObjectType
{
    public static function new(string $name, bool $abstract = false, string ...$declarations): string
    {
        $type = $abstract ? sprintf('abstract type %s', $name) : sprintf('type %s', $name);

        if (0 !== count($declarations)) {
            return sprintf('%s { %s }', $type, implode(' ', array_map(fn (string $declarations): string => $declarations, $declarations)));
        }

        return sprintf('%s {}', $type);
    }
}
