<?php

namespace Tests\Unit\Shared\Infrastructure\Ramsey;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\Shared\Infrastructure\Ramsey\IdGenerator;

class IdGeneratorTest extends TestCase
{
    #[Test]
    public function it_created_different_uuid_every_time(): void
    {
        $generator = new IdGenerator();

        $createdIds = [];
        foreach (range(0, 100) as $i) {
            $id = $generator->generate();
            $stringifiedId = (string) $id;

            $this->assertNotContains($stringifiedId, $createdIds);
            $createdIds[] = $stringifiedId;
        }
    }
}
