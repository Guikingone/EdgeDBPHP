<?php

declare(strict_types=1);

namespace Tests\EdgeDB\EdgeQL\DataDefinition;

use EdgeDB\EdgeQL\DataDefinition\Annotation;
use EdgeDB\EdgeQL\DataDefinition\ScalarType;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class ScalarTypeTest extends TestCase
{
    public function testScalarTypeCanBeCreated(): void
    {
        self::assertSame('CREATE SCALAR TYPE foo', ScalarType::create('foo'));
    }

    public function testScalarTypeCanBeWith(): void
    {
        self::assertSame('WITH bar CREATE SCALAR TYPE foo', ScalarType::create('foo', 'bar'));
    }

    public function testScalarTypeCanBeWhenAbstract(): void
    {
        self::assertSame('CREATE ABSTRACT SCALAR TYPE foo', ScalarType::create('foo', null, true));
    }

    public function testScalarTypeCanBeWhenExtending(): void
    {
        self::assertSame('CREATE SCALAR TYPE foo EXTENDING bar', ScalarType::create('foo', null, false, 'bar'));
    }

    public function testScalarTypeCanBeWithAnnotationSubCommand(): void
    {
        self::assertSame(
            'CREATE SCALAR TYPE foo { CREATE ANNOTATION foo := bar }',
            ScalarType::create('foo', null, false, null, Annotation::create('foo', 'bar'))
        );
    }

    public function testScalarTypeCanBeWithConstraintSubCommand(): void
    {
    }

    public function testScalarTypeCanBeDropped(): void
    {
        self::assertSame('DROP SCALAR TYPE foo', ScalarType::drop('foo'));
    }

    public function testScalarTypeCanBeDroppedWithModule(): void
    {
        self::assertSame('WITH bar DROP SCALAR TYPE foo', ScalarType::drop('foo', 'bar'));
    }
}
