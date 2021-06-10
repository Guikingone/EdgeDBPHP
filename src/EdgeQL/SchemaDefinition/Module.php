<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\SchemaDefinition;

use function count;
use function sprintf;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@internal https://www.edgedb.com/docs/edgeql/sdl/modules}
 */
final class Module
{
    public static function new(string $name, string ...$declarations): string
    {
        $module = sprintf('module %s', $name);

        if (0 !== count($declarations)) {
            return sprintf('%s { %s }', $module, implode(' ', array_map(fn (string $declarations): string => $declarations, $declarations)));
        }

        return sprintf('%s {}', $module);
    }
}
