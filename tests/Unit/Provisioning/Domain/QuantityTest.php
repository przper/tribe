<?php

namespace Tests\Unit\Provisioning\Domain;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\Provisioning\Domain\NegativeQuantityException;
use Przper\Tribe\Provisioning\Domain\Quantity;

class QuantityTest extends TestCase
{
    #[DataProvider('createProvider')]
    #[Test]
    public function canBeCreated(float $value, bool $isValid): void
    {
        if (!$isValid) {
            $this->expectException(NegativeQuantityException::class);
        }

        $quantity = new Quantity($value);

        $this->assertInstanceOf(Quantity::class, $quantity);
    }

    public static function createProvider(): \Generator
    {
        yield [0.0, true];
        yield [1.0, true];
        yield [-1.0, false];
    }
}
