<?php

declare(strict_types=1);

namespace Tests\EdgeDB\EdgeQL\DataDefinition;

use EdgeDB\EdgeQL\DataDefinition\ObjectType;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class ObjectTypeTest extends TestCase
{
    public function testTypeCanBeCreated(): void
    {
    }

    public function testTypeCanBeCreatedWithAbstract(): void
    {
    }

    public function testTypeCanBeCreatedWithExtending(): void
    {
    }

    public function testTypeCanBeAltered(): void
    {
    }

    public function testTypeCanBeDropped(): void
    {
        self::assertSame('DROP TYPE User', ObjectType::drop('User'));
    }
}
