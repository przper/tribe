<?php

namespace Przper\Tribe\Tests\Unit\WorkedTime\Unit\Domain;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\WorkedTime\Domain\Date;
use Przper\Tribe\WorkedTime\Domain\InvalidDateException;

class DateTest extends TestCase
{
    public function test_it_can_be_created_from_valid_data(): void
    {
        $date = Date::fromString('1993-01-09');

        $this->assertInstanceOf(Date::class, $date);
        $this->assertSame('1993-01-09', (string) $date);
    }

    public function test_it_throws_InvalidDateException_for_invalid_data(): void
    {
        $this->expectException(InvalidDateException::class);

        Date::fromString('1');
    }
}
