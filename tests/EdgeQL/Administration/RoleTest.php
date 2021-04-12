<?php

declare(strict_types=1);

namespace Tests\EdgeDB\EdgeQL\Administration;

use EdgeDB\EdgeQL\Administration\Role;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class RoleTest extends TestCase
{
    public function testSuperUserRoleCanBeCreated(): void
    {
        self::assertSame('CREATE SUPERUSER ROLE foo', Role::create('foo', true));
    }

    public function testExtendingRoleCanBeCreated(): void
    {
        self::assertSame('CREATE ROLE foo EXTENDING bar', Role::create('foo', false, 'bar'));
    }

    public function testRoleWithPasswordCanBeCreated(): void
    {
        self::assertSame('CREATE ROLE foo { SET password := bar }', Role::create('foo', false, null, 'bar'));
    }

    public function testRoleCanBeCreated(): void
    {
        self::assertSame('CREATE ROLE foo', Role::create('foo'));
    }

    public function testRoleCanBeAlteredAndRenamed(): void
    {
        self::assertSame('ALTER ROLE foo { RENAME TO bar; };', Role::renameTo('foo', 'bar'));
    }

    public function testRoleCanBeAlteredWithNewPassword(): void
    {
        self::assertSame('ALTER ROLE foo { SET password := bar; };', Role::newPassword('foo', 'bar'));
    }

    public function testRoleCanBeAlteredWithRoleExtension(): void
    {
    }

    public function testRoleCanBeDropped(): void
    {
        self::assertSame('DROP ROLE foo;', Role::drop('foo'));
    }
}