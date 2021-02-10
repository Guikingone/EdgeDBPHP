<?php

declare(strict_types=1);

namespace Tests\EdgeDB\Protocol;

use EdgeDB\Protocol\WriteBuffer;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class WriteBufferTest extends TestCase
{
    public function testBufferCanResetThePosition(): void
    {
        $buffer = new WriteBuffer();
        self::assertSame(0, $buffer->getPosition());

        $buffer->reset();
        self::assertSame(0, $buffer->getPosition());
    }
}
