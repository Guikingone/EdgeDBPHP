<?php

declare(strict_types=1);

namespace EdgeDB\EdgeQL\Statement;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@see https://www.edgedb.com/docs/edgeql/statements/delete}
 */
final class Delete extends Select
{
    public static function delete(string $expression, ?string $with = null, ?string $filter = null, ?string $orderBy = null, ?int $offset = null, ?int $limit = null): string
    {
        $delete = sprintf('%s DELETE', $expression);

        if (null !== $with) {
            $delete = sprintf('WITH %s', $delete);
        }

        if (null !== $filter) {
            $delete = sprintf('%s FILTER %s', $delete, $filter);
        }

        return $delete;
    }
}
