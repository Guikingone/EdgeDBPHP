<?php

declare(strict_types=1);

namespace Tests\EdgeDB\Client;

use EdgeDB\Client\CachedEdgeQLHttpClient;
use EdgeDB\Client\EdgeQLHttpClientInterface;
use EdgeDB\Query\HttpResult;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class CachedEdgeQLHttpClientTest extends TestCase
{
    public function testClientCannotUseCacheWithoutKey(): void
    {
        $pool = $this->createMock(CacheItemPoolInterface::class);
        $pool->expects(self::never())->method('hasItem');

        $httpClient = $this->createMock(EdgeQLHttpClientInterface::class);
        $httpClient->expects(self::once())->method('get')
            ->with(self::equalTo('SELECT Users;'), self::equalTo([]))
            ->willReturn(new HttpResult([]))
        ;

        $client = new CachedEdgeQLHttpClient($httpClient, $pool);
        $client->get('SELECT Users;');
    }
}
