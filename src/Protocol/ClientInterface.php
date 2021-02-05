<?php

declare(strict_types=1);

namespace EdgeDB\Protocol;

use Psr\Log\LoggerInterface;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
interface ClientInterface
{
    /**
     * @param string               $dsn
     * @param array                $options
     * @param LoggerInterface|null $logger
     *
     * @return ClientInterface
     */
    public static function connect(string $dsn, array $options = [], LoggerInterface $logger = null): ClientInterface;

    /**
     * @return array
     */
    public function getOptions(): array;
}
