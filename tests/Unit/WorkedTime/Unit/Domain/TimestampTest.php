<?php

namespace Tests\Unit\WorkedTime\Domain;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\WorkedTime\Domain\Timestamp;

class TimestampTest extends TestCase
{
    public function test_it_can_be_created_when_data_is_valid(): void
    {
        $timestamp = Timestamp::fromDateTimeInterface(new \DateTimeImmutable('01-01-2001 11:11'));

        $this->assertSame('2001-01-01 11:11', (string) $timestamp);
    }
}