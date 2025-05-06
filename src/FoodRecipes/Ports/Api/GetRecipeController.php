<?php

namespace Przper\Tribe\FoodRecipes\Ports\Api;

use Przper\Tribe\FoodRecipes\Application\Query\GetRecipe;
use Przper\Tribe\FoodRecipes\Application\Query\Result\Recipe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetRecipeController extends AbstractController
{
    public function __construct(
        private readonly GetRecipe $getRecipeQuery,
    ) {}

    #[Route('/recipe/{id}', name: 'detail', methods: ['GET'])]
    public function __invoke(string $id): JsonResponse
    {
        $recipe = $this->getRecipeQuery->execute($id);

        if (!$recipe instanceof Recipe) {
            throw $this->createNotFoundException();
        }

        return $this->json($recipe);
    }
}
