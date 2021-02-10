<?php

declare(strict_types=1);

namespace Tests\EdgeDB\Protocol;

use EdgeDB\Protocol\Buffer;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class BufferTest extends TestCase
{
    public function testBufferCanBuild(): void
    {
        $buffer = new Buffer();
        $buffer->add('foo');

        self::assertSame('foo', $buffer->render());
    }

    public function testBufferCanAddNewLine(): void
    {
        $buffer = new Buffer();
        $buffer->add('foo');
        $buffer->newLine();

        self::assertSame('foo\n', $buffer->render());
    }
}
