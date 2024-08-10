<?php

declare(strict_types=1);

namespace Tests\Integration\FoodRecipes\Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectRecipeCreatedTest extends WebTestCase
{
    public function it_persists_projection(): void
    {
        $this->fail();

        $container = static::getContainer();

        $recipeId = (string) $recipeSaved->getId();
        $indexProjection = $projection->getIndexProjection($recipeId);
        $this->assertNotNull($indexProjection);
        $this->assertIsArray($indexProjection);
        $this->assertArrayHasKey('id', $indexProjection);
        $this->assertArrayHasKey('name', $indexProjection);
        $this->assertSame('Chilli con Carne', $indexProjection['name']);

        $detailProjection = $projection->getDetailProjection($recipeId);
        $this->assertNotNull($detailProjection);
        $this->assertIsArray($detailProjection);
        $this->assertArrayHasKey('id', $detailProjection);
        $this->assertArrayHasKey('name', $detailProjection);
        $this->assertSame('Chilli con Carne', $detailProjection['name']);
        $this->assertArrayHasKey('ingredients', $detailProjection);
        $this->assertCount(2, $detailProjection['ingredients']);
        $this->assertSame('Pork: 1 kilogram', $detailProjection['ingredients'][0]);
        $this->assertSame('Tomatoes: 3 can', $detailProjection['ingredients'][1]);

    }
}
