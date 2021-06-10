<?php

declare(strict_types=1);

namespace Tests\EdgeDB\EdgeQL\SchemaDefinition;

use EdgeDB\EdgeQL\SchemaDefinition\Annotation;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class AnnotationTest extends TestCase
{
    public function testAnnotationCanBeCreated(): void
    {
        self::assertSame("annotation foo := 'bar';", Annotation::new('foo', 'bar'));
        self::assertSame("abstract annotation foo := 'bar';", Annotation::new('foo', 'bar', true));
    }
}
