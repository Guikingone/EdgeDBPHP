<?php

declare(strict_types=1);

namespace Tests\EdgeDB\EdgeQL\SchemaDefinition;

use EdgeDB\EdgeQL\SchemaDefinition\ScalarType;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class ScalarTypeTest extends TestCase
{
    public function testTypeCanBeCreated(): void
    {
        self::assertSame('scalar type posint64 {}', ScalarType::new('posint64'));
        self::assertSame('abstract scalar type posint64 {}', ScalarType::new('posint64', true));
    }

    public function testTypeCanBeCreatedWhileExtendingOne(): void
    {
        self::assertSame('scalar type posint64 extending int64 {}', ScalarType::new('posint64', false, 'int64'));
        self::assertSame('abstract scalar type posint64 extending int64 {}', ScalarType::new('posint64', true, 'int64'));
    }
}
