<?php

namespace Przper\Tribe\Shared\Infrastructure\Ramsey;

use Przper\Tribe\Shared\Application\IdGeneratorInterface;
use Przper\Tribe\Shared\Domain\Id;
use Ramsey\Uuid\Uuid;

class IdGenerator implements IdGeneratorInterface
{
    public function generate(): Id
    {
        return new Id(Uuid::uuid7()->toString());
    }
}