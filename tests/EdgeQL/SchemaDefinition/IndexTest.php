<?php

declare(strict_types=1);

namespace Tests\EdgeDB\EdgeQL\SchemaDefinition;

use EdgeDB\EdgeQL\SchemaDefinition\Annotation;
use EdgeDB\EdgeQL\SchemaDefinition\Index;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class IndexTest extends TestCase
{
    public function testIndexCanBeCreated(): void
    {
        self::assertSame('index on (.name)', Index::new('.name'));
    }

    public function testIndexCanBeCreatedWithAnnotations(): void
    {
        self::assertSame(
            "index on (.name) { annotation title := 'User name index'; }",
            Index::new('.name', Annotation::new('title', 'User name index'))
        );
        self::assertSame(
            "index on (.name) { annotation title := 'User name index'; annotation id := 'foo'; }",
            Index::new('.name', Annotation::new('title', 'User name index'), Annotation::new('id', 'foo'))
        );
        self::assertSame(
            "index on (.name) { abstract annotation title := 'User name index'; }",
            Index::new('.name', Annotation::new('title', 'User name index', true))
        );
        self::assertSame(
            "index on (.name) { abstract annotation title := 'User name index'; abstract annotation id := 'foo'; }",
            Index::new('.name', Annotation::new('title', 'User name index', true), Annotation::new('id', 'foo', true))
        );
    }
}
