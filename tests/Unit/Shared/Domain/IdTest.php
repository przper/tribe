<?php

namespace Tests\Unit\Shared\Domain;

use PHPUnit\Framework\TestCase;
use Przper\Tribe\Shared\Domain\Id;

class IdTest extends TestCase
{
    public function test_it_can_be_cast_to_string(): void
    {
        $id = new Id('test');

        $this->assertSame('test', (string) $id);
    }
}
