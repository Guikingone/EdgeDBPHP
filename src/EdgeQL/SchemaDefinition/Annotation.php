<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\SchemaDefinition;

use function sprintf;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@internal https://www.edgedb.com/docs/edgeql/sdl/annotations}
 */
final class Annotation
{
    public static function new(string $name, string $value, bool $abstract = false): string
    {
        return $abstract
            ? sprintf("abstract annotation %s := '%s';", $name, $value)
            : sprintf("annotation %s := '%s';", $name, $value)
        ;
    }
}
