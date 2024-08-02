<?php

namespace Tests\Unit\Shared\Domain;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\Shared\Domain\Collection;

class CollectionTest extends TestCase
{
    #[Test]
    public function it_is_iterable(): void
    {
        $items = ['one', 'two', 'three'];

        $collection = new class ($items) extends Collection {
            /** @phpstan-ignore-next-line  */
            public function __construct(
                public array $items
            ) {}

            protected function getItems(): array
            {
                return $this->items;
            }
        };

        $this->assertInstanceOf(\Traversable::class, $collection);

        foreach ($collection as $key => $item) {
            $this->assertSame($items[$key], $item);
        }
    }
}
