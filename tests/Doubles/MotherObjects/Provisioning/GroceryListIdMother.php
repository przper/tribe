<?php

namespace Tests\Doubles\MotherObjects\Provisioning;

use Przper\Tribe\Provisioning\Domain\GroceryListId;
use Przper\Tribe\Shared\Infrastructure\Ramsey\UuidGenerator;

final class GroceryListIdMother
{
    public static function random(): GroceryListId
    {
        $generator = new UuidGenerator();
        return GroceryListId::fromUuid($generator->generate());
    }
}
