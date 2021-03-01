<?php

declare(strict_types=1);

namespace EdgeDB\Protocol;

use EdgeDB\Exception\BufferError;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class ReadBuffer
{
    private Buffer $buffer;
    private int $position;
    private int $length;

    public function __construct(Buffer $buffer)
    {
        $this->buffer = $buffer;
        $this->position = 0;
    }

    public static function init(ReadBuffer $readBuffer, Buffer $buffer): void
    {
        $readBuffer->buffer = $buffer;
        $readBuffer->position = 0;
        // TODO
    }

    public function finish(): void
    {
        if ($this->length !== $this->position) {
            throw new BufferError('Unexpected trailing data in buffer');
        }
    }

    public function discard(int $size): void
    {
        if ($this->position + $size > $this->length) {
            throw new BufferError('Buffer overread');
        }

        $this->position += $size;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getLength(): int
    {
        return $this->length;
    }
}
