<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\SchemaDefinition;

use function count;
use function sprintf;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@internal https://www.edgedb.com/docs/edgeql/sdl/scalars}
 */
final class ScalarType
{
    public static function new(string $name, bool $abstract = false, ?string $extending = null, string ...$declarations): string
    {
        $type = $abstract ? sprintf('abstract scalar type %s', $name) : sprintf('scalar type %s', $name);

        if (null !== $extending) {
            $type = sprintf('%s extending %s', $type, $extending);
        }

        if (0 !== count($declarations)) {
            return sprintf('%s { %s }', $type, implode(' ', array_map(fn (string $declarations): string => $declarations, $declarations)));
        }

        return sprintf('%s {}', $type);
    }
}
