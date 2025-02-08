<?php

namespace Przper\Tribe\FoodRecipes\Ports\Controller;

use Przper\Tribe\FoodRecipes\Application\Query\GetRecipeDetail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeDetailController extends AbstractController
{
    public function __construct(
        private readonly GetRecipeDetail $getRecipeQuery,
    ) {}

    #[Route('/recipe/{id}', name: 'recipe_detail')]
    public function __invoke(string $id): Response
    {
        $recipe = $this->getRecipeQuery->execute($id);

        return $this->render('food_recipes/detail.html.twig', [
            'recipe' => $recipe,
        ]);
    }
}
