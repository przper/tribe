<?php

namespace Shared;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\Shared\Domain\Id;

class IdTest extends TestCase
{
    public function test_it_can_be_created_from_valid_data(): void
    {
        $id = Id::create('test');

        $this->assertSame('test', $id->getId());
    }

    public function test_it_can_be_cast_to_string(): void
    {
        $id = Id::create('test');

        $this->assertSame('test', (string) $id);
    }
}