<?php

namespace Przper\Tribe\FoodRecipes\Ports\Controller;

use Przper\Tribe\FoodRecipes\Application\Query\GetRecipeDetail;
use Przper\Tribe\FoodRecipes\Application\Query\Result\RecipeDetail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeDetailController extends AbstractController
{
    public function __construct(
        private readonly GetRecipeDetail $getRecipeDetailQuery,
    ) {}

    #[Route('/recipe/{id}', name: 'recipe_detail', methods: ['GET'])]
    public function __invoke(string $id): Response
    {
        $recipe = $this->getRecipeDetailQuery->execute($id);

        if (!$recipe instanceof RecipeDetail) {
            throw $this->createNotFoundException();
        }

        return $this->render('food_recipes/detail.html.twig', [
            'recipe' => $recipe,
        ]);
    }
}
