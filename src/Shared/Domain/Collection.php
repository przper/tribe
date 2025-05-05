<?php

namespace Przper\Tribe\Shared\Domain;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @template TValue
 * @implements IteratorAggregate<int, TValue>
 */
abstract class Collection implements IteratorAggregate, Countable
{
    /**
     * @return array<TValue>
     */
    abstract protected function getItems(): array;

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->getItems());
    }

    public function count(): int
    {
        return count($this->getItems());
    }
}
