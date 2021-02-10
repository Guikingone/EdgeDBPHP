<?php

declare(strict_types=1);

namespace EdgeDB\Protocol;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class WriteBuffer
{
    private Buffer $buffer;
    private int $size;
    private int $position;

    public function __construct()
    {
        $this->buffer = new Buffer();
        $this->size = 4096;
        $this->position = 0;
    }

    public function reset(): void
    {
        $this->position = 0;
    }

    public function writeInt32(int $number): self
    {
        $this->ensureAllocated(4);

        // TODO

        $this->position =+ 4;

        return $this;
    }

    public function unwrap(): Buffer
    {

    }

    public function getPosition(): int
    {
        return $this->position;
    }

    private function ensureAllocated(int $extraLength): void
    {
        $newSize = $this->position + $extraLength;
        if ($newSize > $this->size) {
            $this->reAllocate($newSize);
        }
    }

    private function reAllocate(int $newSize): void
    {

    }
}
