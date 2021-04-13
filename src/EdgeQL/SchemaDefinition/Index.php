<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\SchemaDefinition;

use function count;
use function sprintf;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@internal https://www.edgedb.com/docs/edgeql/sdl/indexes}
 */
final class Index
{
    public static function new(string $on, string ...$annotations): string
    {
        $index = sprintf('index on (%s)', $on);

        if (0 !== count($annotations)) {
            $index = sprintf('%s { %s }', $index, implode(' ', array_map(fn (string $annotation): string => $annotation, $annotations)));
        }

        return $index;
    }
}
