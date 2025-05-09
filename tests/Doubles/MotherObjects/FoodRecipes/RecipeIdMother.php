<?php

namespace Tests\Doubles\MotherObjects\FoodRecipes;

use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Przper\Tribe\Shared\Infrastructure\Ramsey\UuidGenerator;

final class RecipeIdMother
{
    public static function random(): RecipeId
    {
        $generator = new UuidGenerator();
        return RecipeId::fromUuid($generator->generate());
    }
}
