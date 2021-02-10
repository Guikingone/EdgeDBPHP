<?php

declare(strict_types=1);

namespace EdgeDB\Protocol;

use EdgeDB\Exception\BufferError;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class WriteMessageBuffer
{
    private WriteBuffer $buffer;
    private int $messagePosition;

    public function __construct()
    {
        $this->buffer = new WriteBuffer();
        $this->messagePosition = -1;
    }

    public function reset(): self
    {
        $this->messagePosition = -1;
        $this->buffer->reset();

        return $this;
    }

    public function beginMessage(string $value): self
    {
        if ($this->messagePosition >= 0) {
            throw new BufferError('The previous message is not finished');
        }

        $this->messagePosition = $this->buffer->getPosition();
        $this->buffer->writeChar($value);
        $this->buffer->writeInt32(0);

        return $this;
    }

    public function endMessage(): self
    {
        if ($this->messagePosition < 0) {
            throw new BufferError('No current message');
        }

        // TODO

        $this->messagePosition =- 1;

        return $this;
    }

    public function writeChar(string $char): self
    {
        if ($this->messagePosition > 0) {
            throw new BufferError('No current message');
        }

        $this->buffer->writeChar($char);

        return $this;
    }

    public function writeFlush(): self
    {
        if ($this->messagePosition >= 0) {
            throw new BufferError('The previous message is not finished');
        }

        $this->buffer->writeMessage();

        return $this;
    }

    public function unwrap(): Buffer
    {
        if ($this->messagePosition >= 0) {
            throw new BufferError('An unfinished message is in the buffer');
        }

        return $this->buffer->unwrap();
    }
}
