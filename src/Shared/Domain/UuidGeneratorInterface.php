<?php

namespace Przper\Tribe\Shared\Domain;

interface UuidGeneratorInterface
{
    public function generate(): Uuid;
}
