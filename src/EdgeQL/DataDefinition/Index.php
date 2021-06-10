<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\DataDefinition;

use function sprintf;
use function strpos;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@see https://www.edgedb.com/docs/edgeql/ddl/indexes}
 */
final class Index
{
    private const IDENTIFIER = 'INDEX';

    /**
     * {@see https://www.edgedb.com/docs/edgeql/ddl/indexes#create-index}
     */
    public static function create(string $expression, ?string $annotation = null): string
    {
        $create = sprintf('CREATE %s ON (%s)', self::IDENTIFIER, $expression);

        if (null !== $annotation && 0 === strpos($annotation, 'CREATE ANNOTATION')) {
            $create = sprintf('%s { %s }', $create, $annotation);
        }

        return $create;
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/ddl/indexes#alter-index}
     */
    public static function alter(string $expression, ?string $annotation = null): string
    {
        $alter = sprintf('ALTER %s ON (%s)', self::IDENTIFIER, $expression);

        if (null !== $annotation) {
            $alter = sprintf('%s { %s }', $alter, $annotation);
        }

        return $alter;
    }

    /**
     * {@see https://www.edgedb.com/docs/edgeql/ddl/indexes#drop-index}
     */
    public static function drop(string $expression): string
    {
        return sprintf('DROP %s ON (%s)', self::IDENTIFIER, $expression);
    }
}
