<?php

declare(strict_types=1);

namespace EdgeDB\Protocol;

use EdgeDB\Types\Set;
use Error;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class Connection
{
    private bool $operationInProgress = false;
    private ?EventDispatcherInterface $eventDispatcher;
    private LoggerInterface $logger;

    public function connect()
    {
    }

    public function query(string $query, array $arguments = null): Set
    {
        $this->enterOperation();

        try {
            $set = $this->fetch($query, $arguments, false, false);
        } catch (Throwable $exception) {
        } finally {
            $this->leaveOperation();

            return $set;
        }
    }

    public function queryOne(string $query, array $arguments)
    {
    }

    public function queryJson(string $query, array $arguments): string
    {
        $this->enterOperation();

        try {
            $set = $this->fetch($query, $arguments, true, false);
        } catch (Throwable $exception) {
        } finally {
            $this->leaveOperation();

            return $set;
        }
    }

    public function queryJsonOne(string $query, array $arguments): string
    {
        $this->enterOperation();

        try {
            $set = $this->fetch($query, $arguments, true, true);
        } catch (Throwable $exception) {
        } finally {
            $this->leaveOperation();

            return $set;
        }
    }

    public function execute(string $query)
    {
    }

    public function transaction(bool $isolated, bool $readOnly, bool $deferrable): Transaction
    {
    }

    private function fetch(string $query, array $arguments, bool $asJson, bool $expectOne)
    {
    }

    private function enterOperation(): void
    {
        if ($this->operationInProgress) {
            throw new Error('Another operation is in progress. Use multiple separate connections to run operations concurrently.');
        }

        $this->operationInProgress = true;
    }

    private function leaveOperation(): void
    {
        $this->operationInProgress = false;
    }

    private function dispatch(StoppableEventInterface $event): void
    {
        if (null === $this->eventDispatcher) {
            return;
        }

        $this->eventDispatcher->dispatch($event);
    }
}
