<?php

declare(strict_types=1);

namespace EdgeDB\Client;

use EdgeDB\Protocol\ClientInterface;
use EdgeDB\Query\HttpResult;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 *
 * {@see https://www.edgedb.com/docs/clients/90_edgeql/protocol}
 */
interface EdgeQLHttpClientInterface extends ClientInterface
{
    /**
     * @param string               $query
     * @param array<string, mixed> $variables
     *
     * @return HttpResult
     */
    public function get(string $query, array $variables = []): HttpResult;

    /**
     * @param string               $query
     * @param array<string, mixed> $variables
     *
     * @return HttpResult
     */
    public function post(string $query, array $variables = []): HttpResult;
}
