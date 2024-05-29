<?php

namespace Tests\Unit\WorkedTime\Domain;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\WorkedTime\Domain\Timestamp;

class TimestampTest extends TestCase
{
    public function test_it_can_be_created_when_data_is_valid(): void
    {
        $timestamp = Timestamp::fromDateTimeInterface(new \DateTimeImmutable('01-01-2001 11:11'));

        $this->assertSame('2001-01-01 11:11', (string) $timestamp);
    }

    #[DataProvider('isPastTimestampProvider')]
    public function test_it_checks_if_it_is_past_other_timestamp(\DateTime $before, \DateTime $after, bool $expected): void
    {
        $timestamp = Timestamp::fromDateTimeInterface($before);
        $toCheckTimestamp = Timestamp::fromDateTimeInterface($after);
        
        $this->assertEquals($expected, $timestamp->isPastTimestamp($toCheckTimestamp));
    }

    public static function isPastTimestampProvider(): iterable
    {
        yield [new \DateTime('now'), new \DateTime('tomorrow'), false];
        yield [new \DateTime('tomorrow'), new \DateTime('now'), true];
        yield [new \DateTime('now'), new \DateTime('now'), false];
        yield [new \DateTime('2000-01-01'), new \DateTime('2000-01-02'), false];
        yield [new \DateTime('2000-01-02'), new \DateTime('2000-01-01'), true];
    }
}