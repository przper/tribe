<?php

namespace Tests\Unit\Shared\Domain;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\Shared\Domain\Name;

class NameTest extends TestCase
{
    public function test_it_can_be_created_when_data_is_valid(): void
    {
        $name = Name::fromString('Meat');

        $this->assertEquals('Meat', $name);
    }

    #[DataProvider('is_another_Name_data')]
    #[Test]
    public function is_another_Name(string $another, bool $expected): void
    {
        $a = Name::fromString('Meat');
        $b = Name::fromString($another);

        $this->assertSame($expected, $a->is($b));
    }

    public static function is_another_Name_data(): \Generator
    {
        yield ['Meat', true];
        yield ['Cheese', false];
        yield ['meat', true];
        yield ['M eat', false];
        yield ['Meat 1', false];
    }
}
