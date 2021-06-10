<?php

declare(strict_types=1);

namespace Tests\EdgeDB\EdgeQL\Administration;

use EdgeDB\EdgeQL\Administration\Configure;
use EdgeDB\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class ConfigureTest extends TestCase
{
    public function testConfigurationCannotBeCreatedWithInvalidScope(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectDeprecationMessage('The scope "foo" is not allowed');
        self::expectExceptionCode(0);
        Configure::new('foo', 'query_work_mem', '4MB');
    }

    public function testConfigurationCanBeCreated(): void
    {
        self::assertSame(
            "CONFIGURE SYSTEM SET listen_addresses := {'127.0.0.1', '::1'};",
            Configure::new('SYSTEM', 'listen_addresses', '127.0.0.1, ::1')
        );
        self::assertSame(
            "CONFIGURE SESSION SET query_work_mem := '4MB';",
            Configure::new('SESSION', 'query_work_mem', '4MB')
        );
        self::assertSame(
            "CONFIGURE CURRENT DATABASE SET query_work_mem := '4MB';",
            Configure::new('CURRENT DATABASE', 'query_work_mem', '4MB')
        );
    }

    public function testConfigurationCanBeReset(): void
    {
        self::assertSame(
            'CONFIGURE SYSTEM RESET Auth;',
            Configure::reset('SYSTEM', 'Auth')
        );
        self::assertSame(
            'CONFIGURE SYSTEM RESET Auth FILTER Auth.method IS Trust;',
            Configure::reset('SYSTEM', 'Auth', 'Auth.method IS Trust')
        );
    }
}
