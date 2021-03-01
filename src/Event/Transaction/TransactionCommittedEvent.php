<?php

declare(strict_types=1);

namespace EdgeDB\Event\Transaction;

use Psr\EventDispatcher\StoppableEventInterface;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class TransactionCommittedEvent implements StoppableEventInterface
{
    /**
     * {@inheritdoc}
     */
    public function isPropagationStopped(): bool
    {
        return true;
    }
}
