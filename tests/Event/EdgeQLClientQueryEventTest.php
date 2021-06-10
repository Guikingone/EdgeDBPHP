<?php

declare(strict_types=1);

namespace Tests\EdgeDB\Event;

use EdgeDB\Event\EdgeQLClientQueryEvent;
use PHPUnit\Framework\TestCase;

/**
 * @author Guillaume Loulier <contact@guillaumeloulier.fr>
 */
final class EdgeQLClientQueryEventTest extends TestCase
{
    public function testEventDataCanBeRetrieved(): void
    {
        $event = new EdgeQLClientQueryEvent('GET', "SELECT User FILTER User.name = 'John';");

        self::assertSame('GET', $event->getMethod());
        self::assertSame("SELECT User FILTER User.name = 'John';", $event->getQuery());
        self::assertEmpty($event->getVariables());
    }
}
