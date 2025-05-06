<?php

namespace Przper\Tribe\FoodRecipes\Ports\Web;

use Przper\Tribe\FoodRecipes\Application\Query\GetRecipes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeIndexController extends AbstractController
{
    public function __construct(
        private readonly GetRecipes $getRecipesQuery,
    ) {}

    #[Route('/recipe', name: 'recipe_index')]
    public function __invoke(): Response
    {
        $recipes = $this->getRecipesQuery->execute();

        return $this->render('food_recipes/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }
}
