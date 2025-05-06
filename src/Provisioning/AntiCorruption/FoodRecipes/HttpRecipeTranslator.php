<?php

namespace Przper\Tribe\Provisioning\AntiCorruption\FoodRecipes;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class HttpRecipeTranslator implements RecipeTranslatorInterface
{
    public function __construct(
        #[Autowire(service: 'tribe.internal_http_client')]
        private HttpClientInterface $httpClient,
    ) {}

    public function translate(string $recipeId): ?Recipe
    {
        $response = $this->httpClient->request('GET', "/api/v1/recipe/$recipeId");

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        $response = $response->toArray();

        $ingredients = array_map(
            fn(array $i) => new RecipeIngredient($i['name'], $i['quantity'], $i['unit']),
            $response['ingredients'],
        );

        return new Recipe(
            ingredients: $ingredients,
        );
    }
}
