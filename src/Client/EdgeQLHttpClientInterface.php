<?php

declare(strict_types=1);

namespace EdgeDB\Client;

use EdgeDB\Query\HttpResult;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface EdgeQLHttpClientInterface
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
