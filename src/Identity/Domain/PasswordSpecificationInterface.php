<?php

namespace Przper\Tribe\Identity\Domain;

interface PasswordSpecificationInterface
{
    public function isSatisfiedBy(Password $password): bool;
}