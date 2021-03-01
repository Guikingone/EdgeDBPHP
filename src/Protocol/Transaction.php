<?php

declare(strict_types=1);

namespace EdgeDB\Protocol;

use EdgeDB\Event\Transaction\TransactionStartedEvent;
use Error;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Throwable;
use function sprintf;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class Transaction
{
    public const NEW = 0;
    public const STARTED = 1;
    public const COMMITTED = 2;
    public const ROLLED_BACK = 3;
    public const FAILED = 4;

    private ?int $id;
    private Connection $connection;
    private ?bool $deferrable;
    private ?bool $isolation;
    private bool $managed;
    private bool $nested;
    private ?bool $readonly;
    private int $state;
    private LoggerInterface $logger;
    private ?EventDispatcherInterface $eventDispatcher;

    /**
     * @param Connection                    $connection
     * @param array<string, bool|null>      $options
     * @param LoggerInterface|null          $logger
     * @param EventDispatcherInterface|null $eventDispatcher
     */
    public function __construct(
        Connection $connection,
        array $options = [],
        ?LoggerInterface $logger = null,
        ?EventDispatcherInterface $eventDispatcher = null
    ) {
        $this->connection = $connection;
        $this->deferrable = $options['deferrable'];
        $this->id = null;
        $this->isolation = $options['isolation'];
        $this->managed = false;
        $this->nested = false;
        $this->readonly = $options['readonly'];
        $this->state = Transaction::NEW;
        $this->logger = $logger ?: new NullLogger();
        $this->eventDispatcher = $eventDispatcher;
    }

    public function start(): void
    {
        $this->checkStateBase('start');

        if ($this->state === Transaction::STARTED) {
            throw new Error('Cannot start, the transaction is already started');
        }

        $this->execute('', Transaction::STARTED);

        $this->dispatch(new TransactionStartedEvent());
    }

    public function commit(): void
    {
        $this->checkStateBase('commit');

        // TODO

        if (null !== $this->nested && $this->nested) {
            $this->execute(sprintf('RELEASE SAVEPOINT %d;', $this->id), Transaction::COMMITTED);
        }

        $this->execute('COMMIT;', Transaction::COMMITTED);
    }

    public function rollback(): void
    {
        $this->checkStateBase('rollback');

        // TODO

        if (null !== $this->nested && $this->nested) {
            $this->execute(sprintf('ROLLBACK TO SAVEPOINT %d;', $this->id), Transaction::ROLLED_BACK);
        }

        $this->execute('ROLLBACK;', Transaction::ROLLED_BACK);
    }

    public function isActive(): bool
    {
        return $this->state === Transaction::STARTED;
    }

    public function getState(): int
    {
        return $this->state;
    }

    /**
     * @param string $query
     * @param int    $state
     *T
     * @throws Throwable
     */
    private function execute(string $query, int $state): void
    {
        try {
            $this->connection->execute($query);
            $this->state = $state;
        } catch (Throwable $exception) {
            $this->state = Transaction::FAILED;
            $this->logger->critical('The transaction cannot be executed due to an error', [
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    private function checkStateBase(string $operation): void
    {
        if ($this->state === Transaction::COMMITTED) {
            throw new Error(sprintf('Cannot %s, the transaction is already committed', $operation));
        }

        if ($this->state === Transaction::ROLLED_BACK) {
            throw new Error(sprintf('Cannot %s, the transaction is already rolled back', $operation));
        }

        if ($this->state === Transaction::FAILED) {
            throw new Error(sprintf('Cannot %s, the transaction is in error state', $operation));
        }
    }

    private function dispatch(StoppableEventInterface $event): void
    {
        if (null === $this->eventDispatcher) {
            return;
        }

        $this->eventDispatcher->dispatch($event);
    }
}
