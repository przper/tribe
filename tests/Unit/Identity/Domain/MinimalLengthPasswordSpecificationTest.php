<?php

namespace Tests\Unit\Identity\Domain;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\Identity\Domain\MinimalLengthPasswordSpecification;
use Przper\Tribe\Identity\Domain\Password;

class MinimalLengthPasswordSpecificationTest extends TestCase
{
    #[Test]
    #[DataProvider('passwordProvider')]
    public function isSatisfiedBy(string $passwordValue, bool $expectedResult): void
    {
        $minimalLength = 8;
        $sut = new MinimalLengthPasswordSpecification($minimalLength);
        $password = Password::fromString($passwordValue);

        $this->assertSame($expectedResult, $sut->isSatisfiedBy($password));
    }

    public static function passwordProvider(): \Generator
    {
        yield ['validPass123', true];
        yield ['short', false];
        yield ['exactly8', true];
    }
}