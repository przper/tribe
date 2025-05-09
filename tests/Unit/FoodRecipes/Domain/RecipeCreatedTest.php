<?php

namespace Tests\Unit\FoodRecipes\Domain;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Przper\Tribe\FoodRecipes\Domain\RecipeCreated;
use Tests\Doubles\MotherObjects\FoodRecipes\RecipeIdMother;

class RecipeCreatedTest extends TestCase
{
    #[Test]
    public function it_can_be_created_from_valid_data(): void
    {
        $id = RecipeIdMother::random();

        $event = RecipeCreated::create($id);

        $this->assertSame((string) $id, $event->aggregateId);
        $this->assertSame('recipe_created', $event->name);
        $this->assertSame(1, $event->version);
    }
}
