<?php

namespace Przper\Tribe\Shared\Domain;

use Traversable;

abstract class Collection implements \IteratorAggregate
{
    abstract protected function getItems(): array;

    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->getItems());
    }
}
