<?php

declare(strict_types=1);

namespace Tests\EdgeDB\EdgeQL\DataDefinition;

use EdgeDB\EdgeQL\DataDefinition\Annotation;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class AnnotationTest extends TestCase
{
    public function testAbstractAnnotationCanBeCreated(): void
    {
        self::assertSame(
            "CREATE ABSTRACT ANNOTATION foo := 'bar';",
            Annotation::createAbstract('foo', 'bar')
        );
    }

    public function testAbstractAnnotationCanBeAltered(): void
    {
        self::assertSame(
            "ALTER ABSTRACT ANNOTATION foo := 'bar';",
            Annotation::alterAbstract('foo', 'bar')
        );
        self::assertSame(
            "ALTER ABSTRACT ANNOTATION foo := 'bar' { RENAME TO random; };",
            Annotation::alterAbstract('foo', 'bar', 'RENAME TO random')
        );
        self::assertSame(
            "ALTER ABSTRACT ANNOTATION foo := 'bar' { ALTER ANNOTATION random := 'bar'; };",
            Annotation::alterAbstract('foo', 'bar', Annotation::alter('random', 'bar'))
        );
        self::assertSame(
            "ALTER ABSTRACT ANNOTATION foo := 'bar' { DROP ANNOTATION random; };",
            Annotation::alterAbstract('foo', 'bar', Annotation::drop('random'))
        );
    }

    public function testAbstractAnnotationCanBeDropped(): void
    {
        self::assertSame('DROP ABSTRACT ANNOTATION foo;', Annotation::dropAbstract('foo'));
    }

    public function testAnnotationCanBeCreated(): void
    {
        self::assertSame("CREATE ANNOTATION foo := 'bar';", Annotation::create('foo', 'bar'));
    }

    public function testAnnotationCanBeAltered(): void
    {
        self::assertSame("ALTER ANNOTATION foo := 'bar';", Annotation::alter('foo', 'bar'));
    }

    public function testAnnotationCanBeDropped(): void
    {
        self::assertSame('DROP ANNOTATION foo;', Annotation::drop('foo'));
    }
}
