<?php

namespace Tests\Unit\Shared\Domain;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\Shared\Domain\DomainEvent;
use Przper\Tribe\Shared\Domain\DomainEventDispatcher;
use Przper\Tribe\Shared\Domain\DomainEventListenerInterface;

class DomainEventDispatcherTest extends TestCase
{
    #[Test]
    public function it_dispatches_events(): void
    {
        $dummyListener = new class () implements DomainEventListenerInterface {
            /** @phpstan-ignore-next-line */
            public array $handledEvents = [];

            public function handle(DomainEvent $event): void
            {
                $this->handledEvents[] = $event->name;
            }
        };

        $event1 = new readonly class () extends DomainEvent {
            public function __construct()
            {
                parent::__construct('1', 'Event_1', 1);
            }
        };
        $event2 = new readonly class () extends DomainEvent {
            public function __construct()
            {
                parent::__construct('1', 'Event_2', 1);
            }
        };

        $dispatcher = new DomainEventDispatcher([$dummyListener]);
        $dispatcher->dispatch($event1, $event2);

        $this->assertCount(2, $dummyListener->handledEvents);
        $this->assertSame('Event_1', $dummyListener->handledEvents[0]);
        $this->assertSame('Event_2', $dummyListener->handledEvents[1]);
    }
}
