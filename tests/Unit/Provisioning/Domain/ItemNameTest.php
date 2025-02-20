<?php

namespace Tests\Unit\Provisioning\Domain;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\Provisioning\Domain\ItemName;

final class ItemNameTest extends TestCase
{
    #[DataProvider('isEqualToProvider')]
    #[Test]
    public function isEqualTo(mixed $compare, bool $expectedResult): void
    {
        $sut = new ItemName('Test');
        $this->assertSame($expectedResult, $sut->isEqualTo($compare));
    }

    public static function isEqualToProvider(): \Generator
    {
        yield [new ItemName('Test'), true];
        yield [new ItemName('Different'), false];
    }
}
