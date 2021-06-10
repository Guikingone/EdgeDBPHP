<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\DataDefinition;

use EdgeDB\EdgeQL\Statement\With;
use function count;
use function implode;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@see https://www.edgedb.com/docs/edgeql/ddl/aliases}
 */
final class Alias
{
    private const IDENTIFIER = 'ALIAS';

    /**
     * @param string      $alias
     * @param string|null $with
     * @param string|null $using
     * @param string[]    $annotations
     * @param string[]    $modules
     * @param string|null $module
     *
     * @return string
     *
     * {@see https://www.edgedb.com/docs/edgeql/ddl/aliases#create-alias}
     */
    public static function create(string $alias, string $with = null, string $using = null, array $annotations = [], array $modules = [], string $module = null): string
    {
        $alias = sprintf('CREATE %s %s', self::IDENTIFIER, $alias);

        if (null !== $with) {
            $alias = sprintf('%s %s', With::new($with), $alias);
        }

        if (null !== $using) {
            $alias = 0 === count($annotations)
                ? sprintf('%s { USING %s; }', $alias, $using)
                : sprintf('%s { USING %s; %s }', $alias, $using, implode('; ', $annotations))
            ;
        }

        if ([] !== $annotations && null === $using) {
            $alias = sprintf('%s { %s }', $alias, implode('; ', $annotations));
        }

        if (0 !== count($modules) && null !== $module) {
            $alias = sprintf('%s; %s MODULE %s', $alias, implode(', ', $modules), $module);
        }

        return sprintf('%s;', $alias);
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/ddl/aliases#drop-alias}
     */
    public static function drop(string $alias, ?string $with = null): string
    {
        return null !== $with
            ? sprintf('%s DROP %s %s;', With::new($with), self::IDENTIFIER, $alias)
            : sprintf('DROP %s %s;', self::IDENTIFIER, $alias)
        ;
    }
}
