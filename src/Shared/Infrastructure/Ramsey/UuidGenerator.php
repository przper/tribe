<?php

namespace Przper\Tribe\Shared\Infrastructure\Ramsey;

use Przper\Tribe\Shared\Domain\Uuid;
use Przper\Tribe\Shared\Domain\UuidGeneratorInterface;
use Ramsey\Uuid\Uuid as RamseyUuid;

class UuidGenerator implements UuidGeneratorInterface
{
    public function generate(): Uuid
    {
        return new Uuid(RamseyUuid::uuid7()->toString());
    }
}
