<?php

namespace Przper\Tribe\Shared\Domain;

class NegativeQuantityException extends \Exception
{
    /** @phpstan-ignore missingType.property */
    protected $message = "Quantity must have a non-negative value";
}
