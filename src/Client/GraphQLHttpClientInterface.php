<?php

declare(strict_types=1);

namespace EdgeDB\Client;

use EdgeDB\Query\HttpResult;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface GraphQLHttpClientInterface
{
    public function get(string $query, ?string $operationName = null, ?array $options = []): HttpResult;

    public function post(string $query, ?string $operationName = null, ?array $options = []): HttpResult;
}
