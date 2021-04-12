<?php

declare(strict_types=1);

namespace Tests\EdgeDB\EdgeQL\DataDefinition;

use EdgeDB\EdgeQL\DataDefinition\Alias;
use EdgeDB\EdgeQL\DataDefinition\Annotation;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class AliasTest extends TestCase
{
    public function testAliasCanBeCreated(): void
    {
        self::assertSame('CREATE ALIAS foo;', Alias::create('foo'));
    }

    public function testAliasCanBeCreatedWithModule(): void
    {
        self::assertSame('WITH MODULE bar CREATE ALIAS foo;', Alias::create('foo', 'bar'));
    }

    public function testAliasCanBeCreatedWithExpression(): void
    {
        self::assertSame(
            'CREATE ALIAS foo { USING SELECT Users; };',
            Alias::create('foo', null, 'SELECT Users')
        );
    }

    public function testAliasCanBeCreatedWithConstraint(): void
    {
        self::assertSame(
            'CREATE ALIAS foo { CREATE ANNOTATION foo := bar; };',
            Alias::create('foo', null, null, [
                Annotation::create('foo', 'bar'),
            ])
        );
    }

    public function testAliasCanBeCreatedWithExpressionAndConstraint(): void
    {
        self::assertSame(
            'CREATE ALIAS foo { USING SELECT Users; CREATE ANNOTATION foo := bar; };',
            Alias::create('foo', null, 'SELECT Users', [
                Annotation::create('foo', 'bar'),
            ])
        );
    }

    public function testAliasCanBeCreatedWithModuleList(): void
    {
        self::assertSame(
            'CREATE ALIAS foo; foo, bar MODULE random;',
            Alias::create('foo', null, null, [], ['foo', 'bar'], 'random')
        );
    }

    public function testAliasCanBeDropped(): void
    {
        self::assertSame('DROP ALIAS foo;', Alias::drop('foo'));
    }

    public function testAliasCanBeDroppedWithModule(): void
    {
        self::assertSame('WITH MODULE bar DROP ALIAS foo;', Alias::drop('foo', 'bar'));
    }
}
