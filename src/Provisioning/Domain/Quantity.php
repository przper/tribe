<?php

namespace Przper\Tribe\Provisioning\Domain;

final readonly class Quantity
{
    /**
     * @throws NegativeQuantityException
     */
    public function __construct(
        public float $value,
    ) {
        $this->guard();
    }

    /**
     * @throws NegativeQuantityException
     */
    private function guard(): void
    {
        if ($this->value < 0) {
            throw new NegativeQuantityException();
        }
    }
}
