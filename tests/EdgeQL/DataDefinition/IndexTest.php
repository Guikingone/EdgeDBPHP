<?php

declare(strict_types=1);

namespace Tests\EdgeDB\EdgeQL\DataDefinition;

use EdgeDB\EdgeQL\DataDefinition\Annotation;
use EdgeDB\EdgeQL\DataDefinition\Index;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class IndexTest extends TestCase
{
    public function testIndexCanBeCreated(): void
    {
        self::assertSame('CREATE INDEX ON (.name)', Index::create('.name'));
        self::assertSame(
            'CREATE INDEX ON (.name) { CREATE ANNOTATION foo := bar; }',
            Index::create('.name', Annotation::create('foo', 'bar'))
        );
    }

    public function testIndexCanBeAltered(): void
    {
        self::assertSame('ALTER INDEX ON (.name)', Index::alter('.name'));
        self::assertSame(
            'ALTER INDEX ON (.name) { CREATE ANNOTATION foo := bar; }',
            Index::alter('.name', Annotation::create('foo', 'bar'))
        );
        self::assertSame(
            'ALTER INDEX ON (.name) { ALTER ANNOTATION foo := bar; }',
            Index::alter('.name', Annotation::alter('foo', 'bar'))
        );
        self::assertSame(
            'ALTER INDEX ON (.name) { DROP ANNOTATION foo; }',
            Index::alter('.name', Annotation::drop('foo'))
        );
    }

    public function testIndexCanBeDropped(): void
    {
        self::assertSame('DROP INDEX ON (.name)', Index::drop('.name'));
    }
}
