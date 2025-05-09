<?php

namespace Przper\Tribe\Identity\Domain;

interface EmailSpecificationInterface
{
    public function isSatisfiedBy(Email $email): bool;
}
