<?php

namespace Przper\Tribe\FoodRecipes\Ports\Controller;

use Przper\Tribe\FoodRecipes\Application\Query\GetRecipe;
use Przper\Tribe\FoodRecipes\Domain\RecipeId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeDetailController extends AbstractController
{
    public function __construct(
        private readonly GetRecipe $getRecipeQuery,
    ) {}

    #[Route('/recipe/{id}', name: 'recipe_detail')]
    public function __invoke(string $id): Response
    {
        $recipe = $this->getRecipeQuery->execute(new RecipeId($id));

        $html = <<<HTML
                <html>
                    <head></head>
                    <body>
                        <div>
                            <h1>{$recipe->name}</h1>
                            
                            <div>
                                WIP
                            </div>
                        </div>
                    </body>
                </html>
            HTML;

        return new Response($html);
    }
}
