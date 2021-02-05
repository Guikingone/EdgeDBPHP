<?php

declare(strict_types=1);

namespace EdgeDB\Protocol;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class AsyncClient implements ClientInterface
{
    public static function connect(string $dsn, array $options = []): ClientInterface
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {

    }
}
