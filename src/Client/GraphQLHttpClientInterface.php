<?php

declare(strict_types=1);

namespace EdgeDB\Client;

use EdgeDB\Query\HttpResult;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@see https://www.edgedb.com/docs/clients/99_graphql/protocol}
 */
interface GraphQLHttpClientInterface
{
    /**
     * @param string                    $query
     * @param string|null               $operationName
     * @param array<string, mixed>|null $options
     *
     * @return HttpResult
     */
    public function get(string $query, ?string $operationName = null, ?array $options = []): HttpResult;

    /**
     * @param string                    $query
     * @param string|null               $operationName
     * @param array<string, mixed>|null $options
     *
     * @return HttpResult
     */
    public function post(string $query, ?string $operationName = null, ?array $options = []): HttpResult;
}
