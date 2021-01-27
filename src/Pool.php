<?php

declare(strict_types=1);

namespace EdgeDB;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class Pool
{
    /**
     * @var bool
     */
    private $closed;

    /**
     * @var Client[]
     */
    private $connections;
}
